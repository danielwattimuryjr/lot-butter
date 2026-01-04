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
    Simple structure: week, intercept, slope, trend, seasonal_index, forecast_value
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
    
    # Get intercept and slope from forecast_result metadata
    intercept = getattr(forecast_result, 'intercept', None)
    slope = getattr(forecast_result, 'slope', None)
    
    # Use continuous_week if available, otherwise calculate from index
    if 'continuous_week' in forecast_data.columns:
        forecast_data['week'] = forecast_data['continuous_week']
    else:
        # Fallback: extract week from index
        print("⚠️ Warning: continuous_week not found, using index position")
        return
    
    # Insert forecast data
    for idx, row in forecast_data.iterrows():
        # Get values for this specific week
        week_number = int(row['week'])
        seasonal_value = row.get('seasonal', None)
        trend_value = row.get('trend', None)
        forecast_value = row.get('forecast')
        
        # Calculate month (1-12, based on 4 weeks per month, repeating every 48 weeks)
        month = ((week_number - 1) % 48) // 4 + 1
        
        # Get actual year from datetime index (2025, 2026, etc.)
        year = idx.year
        
        conn.execute(
            text("""
                INSERT INTO forecasts
                (product_id, week, month, year, intercept, slope, trend, seasonal_index, forecast_value, created_at, updated_at)
                VALUES (:product_id, :week, :month, :year, :intercept, :slope, :trend, :seasonal_index, :forecast_value, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    month = VALUES(month),
                    year = VALUES(year),
                    intercept = VALUES(intercept),
                    slope = VALUES(slope),
                    trend = VALUES(trend),
                    seasonal_index = VALUES(seasonal_index),
                    forecast_value = VALUES(forecast_value),
                    updated_at = NOW()
            """),
            {
                "product_id": product_id,
                "week": week_number,
                "month": month,
                "year": year,
                "intercept": float(intercept) if intercept and pd.notna(intercept) else None,
                "slope": float(slope) if slope and pd.notna(slope) else None,
                "trend": float(trend_value) if trend_value and pd.notna(trend_value) else None,
                "seasonal_index": float(seasonal_value) if seasonal_value and pd.notna(seasonal_value) else None,
                "forecast_value": float(forecast_value)
            }
        )
    
    print(f"✓ Saved {len(forecast_data)} forecast records for product_id {product_id}")
    print(f"  - Week range: {forecast_data['week'].min()} to {forecast_data['week'].max()}")
    print(f"  - Intercept: {intercept:.4f}, Slope: {slope:.4f}")
