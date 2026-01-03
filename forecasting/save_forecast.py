from sqlalchemy import text
from db import engine
import pandas as pd
from datetime import datetime

def save_forecast(product_id, forecast_result, conn=None):
    """
    Save forecast results to the database.
    Only saves to forecasts table - MPS is calculated on frontend.
    
    Args:
        product_id: The product ID
        forecast_result: DataFrame from run_forecast with forecast data
        conn: Database connection (optional, will create new transaction if None)
    """
    if conn is None:
        with engine.begin() as conn:
            _save_forecast_internal(product_id, forecast_result, conn)
    else:
        _save_forecast_internal(product_id, forecast_result, conn)

def _save_forecast_internal(product_id, forecast_result, conn):
    """
    Internal function to save forecast results using provided connection.
    """
    # Delete existing forecasts for this product
    conn.execute(
        text("DELETE FROM forecasts WHERE product_id = :product_id"),
        {"product_id": product_id}
    )
    
    # Get only forecast rows (rows with forecast values)
    forecast_data = forecast_result[forecast_result['forecast'].notna()].copy()
    
    if forecast_data.empty:
        print("No forecast data to save")
        return
    
    # Extract year and week from the datetime index
    forecast_data['year'] = forecast_data.index.year
    forecast_data['week'] = forecast_data.index.isocalendar().week
    
    # Get the last historical data for components if available
    historical_data = forecast_result[forecast_result['actual'].notna()]
    
    # Calculate average trend and residual (but NOT seasonal - each week has its own seasonal index)
    if not historical_data.empty and 'trend' in historical_data.columns:
        avg_trend = historical_data['trend'].mean()
        avg_residual = historical_data['residual'].mean()
    else:
        avg_trend = None
        avg_residual = None
    
    # Insert forecast data - each row uses its own seasonal value
    for idx, row in forecast_data.iterrows():
        # Get seasonal value for this specific week (not average!)
        seasonal_value = row.get('seasonal', None)
        
        conn.execute(
            text("""
                INSERT INTO forecasts
                (product_id, year, week, trend_component, seasonal_component, 
                 irregular_component, forecast_value, created_at, updated_at)
                VALUES (:product_id, :year, :week, :trend, :seasonal, 
                        :irregular, :forecast, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    trend_component = VALUES(trend_component),
                    seasonal_component = VALUES(seasonal_component),
                    irregular_component = VALUES(irregular_component),
                    forecast_value = VALUES(forecast_value),
                    updated_at = NOW()
            """),
            {
                "product_id": product_id,
                "year": int(row['year']),
                "week": int(row['week']),
                "trend": float(avg_trend) if avg_trend and pd.notna(avg_trend) else None,
                "seasonal": float(seasonal_value) if seasonal_value and pd.notna(seasonal_value) else None,
                "irregular": float(avg_residual) if avg_residual and pd.notna(avg_residual) else None,
                "forecast": float(row['forecast'])
            }
        )
    
    print(f"✓ Saved {len(forecast_data)} forecast records for product_id {product_id}")
