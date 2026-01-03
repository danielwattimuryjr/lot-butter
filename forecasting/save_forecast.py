from sqlalchemy import text
from db import engine
import pandas as pd
from datetime import datetime

def save_forecast(product_id, forecast_result, conn=None):
    """
    Save forecast results to the database.
    
    Args:
        product_id: The product ID
        forecast_result: DataFrame from run_forecast with forecast data
        conn: Database connection (optional, will create new transaction if None)
    """
    if conn is None:
        # If no connection provided, create a new transaction
        with engine.begin() as conn:
            _save_forecast_internal(product_id, forecast_result, conn)
    else:
        # Use provided connection (part of larger transaction)
        _save_forecast_internal(product_id, forecast_result, conn)

def _save_forecast_internal(product_id, forecast_result, conn):
    """
    Internal function to save forecast results using provided connection.
    """
    # Store ALL edited MPS rows (not just week 0) before deleting forecasts
    edited_mps_rows = conn.execute(
        text("""
            SELECT id, year, week, month, 
                   mps_value, available, projected_on_hand
            FROM master_production_schedules
            WHERE is_edited = 1 AND product_id = :product_id
        """),
        {"product_id": product_id}
    ).fetchall()
    
    edited_mps_data = {
        (row[1], row[2], row[3]): {  # Key: (year, week, month)
            'id': row[0],
            'mps_value': row[4],
            'available': row[5],
            'projected_on_hand': row[6]
        } for row in edited_mps_rows
    }
    
    # Delete MPS records for this product (all will be recreated)
    conn.execute(
        text("""
            DELETE FROM master_production_schedules 
            WHERE product_id = :product_id
        """),
        {"product_id": product_id}
    )
    
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
    
    # Extract year, week, and month from the datetime index
    forecast_data['year'] = forecast_data.index.year
    forecast_data['week'] = forecast_data.index.isocalendar().week
    forecast_data['month'] = forecast_data.index.month  # Full month name
    
    # Get the last historical data for components if available
    historical_data = forecast_result[forecast_result['actual'].notna()]
    
    # Calculate decomposition components
    if not historical_data.empty and 'trend' in historical_data.columns:
        avg_trend = historical_data['trend'].mean()
        avg_seasonal = historical_data['seasonal'].mean()
        avg_residual = historical_data['residual'].mean()
    else:
        avg_trend = None
        avg_seasonal = None
        avg_residual = None
    
    # Track forecast IDs for MPS creation
    forecast_ids = []
    
    # Insert forecast data
    for idx, row in forecast_data.iterrows():
        result = conn.execute(
            text("""
                INSERT INTO forecasts
                (product_id, year, week, month, trend_component, seasonal_component, 
                 irregular_component, forecast_value, created_at, updated_at)
                VALUES (:product_id, :year, :week, :month, :trend, :seasonal, 
                        :irregular, :forecast, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    month = VALUES(month),
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
                "month": str(row['month']),
                "trend": float(avg_trend) if avg_trend and pd.notna(avg_trend) else None,
                "seasonal": float(avg_seasonal) if avg_seasonal and pd.notna(avg_seasonal) else None,
                "irregular": float(avg_residual) if avg_residual and pd.notna(avg_residual) else None,
                "forecast": float(row['forecast'])
            }
        )
        forecast_ids.append(result.lastrowid)
    
    print(f"Saved {len(forecast_data)} forecast records for product_id {product_id}")
    
    # Generate MPS records
    create_mps_records(conn, product_id, forecast_data, forecast_ids, edited_mps_data)

def create_mps_records(conn, product_id, forecast_data, forecast_ids, edited_mps_data=None):
    """
    Create Master Production Schedule records based on forecast data.
    
    Args:
        conn: Database connection
        product_id: The product ID
        forecast_data: DataFrame with forecast information
        forecast_ids: List of forecast IDs that were just inserted
        edited_mps_data: Dict of preserved edited MPS rows {(year, week, month): {...}}
    """
    if edited_mps_data is None:
        edited_mps_data = {}
    
    # Add forecast IDs to dataframe
    forecast_data['forecast_id'] = forecast_ids
    
    # Sort forecast data
    forecast_data = forecast_data.sort_index()
    
    # Track month changes to set projected_on_hand to 0 at month start
    prev_month = None
    prev_available = 0
    prev_projected_on_hand = 0  # Track last week's projected on hand
    first_week_tracker = {}  # Track first week insertion for each month
    
    for idx, (date_idx, row) in enumerate(forecast_data.iterrows()):
        current_month = row['month']
        current_year = int(row['year'])
        current_week = int(row['week'])
        forecast_value = int(round(row['forecast']))
        forecast_id = row['forecast_id']
        
        # Check if this week has an edited MPS row
        edited_key = (current_year, current_week, current_month)
        
        # Check if this is the first week of a new month
        if current_month != prev_month:
            # Check for edited week 0 (First Stock) for this month
            week_0_key = (current_year, 0, current_month)
            
            if week_0_key in edited_mps_data:
                # Reuse edited First Stock
                edited_row = edited_mps_data[week_0_key]
                prev_available = edited_row['projected_on_hand']
                prev_projected_on_hand = edited_row['projected_on_hand']
                print(f"✓ Reusing edited 'First Stock' for year {current_year}, month {current_month} with POH: {prev_available}")
            else:
                # Insert new First Stock row
                conn.execute(
                    text("""
                        INSERT INTO master_production_schedules
                        (product_id, forecast_id, year, week, month, forecast_value, mps_value, available, projected_on_hand, is_edited, created_at, updated_at)
                        VALUES (:product_id, NULL, :year, 0, :month, NULL, NULL, NULL, 0, 0, NOW(), NOW())
                    """),
                    {
                        "product_id": product_id,
                        "year": current_year,
                        "month": current_month
                    }
                )
                print(f"✓ Inserted new 'First Stock' row for year {current_year}, month {current_month}")
                prev_available = 0
                prev_projected_on_hand = 0
            
            projected_on_hand = 0
            prev_month = current_month
        else:
            projected_on_hand = 0  # Always 0 based on requirements
        
        # Check if this specific week was edited
        if edited_key in edited_mps_data:
            # Use edited values
            edited_row = edited_mps_data[edited_key]
            projected_on_hand = edited_row['projected_on_hand']
            
            # mps_value = current week forecast - last week projected_on_hand
            mps_value = forecast_value - prev_projected_on_hand
            
            # available = available(week-1) + mps(week) - forecast(week)
            available = prev_available + mps_value - forecast_value
            
            # Insert the MPS record with is_edited = 1
            conn.execute(
                text("""
                    INSERT INTO master_production_schedules
                    (product_id, forecast_id, year, week, month, forecast_value, mps_value, available, projected_on_hand, is_edited, created_at, updated_at)
                    VALUES (:product_id, :forecast_id, :year, :week, :month, :forecast_value, :mps, :avail, :poh, 1, NOW(), NOW())
                """),
                {
                    "product_id": product_id,
                    "forecast_id": forecast_id,
                    "year": current_year,
                    "week": current_week,
                    "month": current_month,
                    "forecast_value": forecast_value,
                    "mps": mps_value,
                    "avail": available,
                    "poh": projected_on_hand
                }
            )
            print(f"✓ Restored edited MPS for year {current_year}, week {current_week}, month {current_month}")
        else:
            # Normal calculation for non-edited rows
            # mps_value = current week forecast - last week projected_on_hand
            mps_value = forecast_value - prev_projected_on_hand
            
            # available = available(week-1) + mps(week) - forecast(week)
            available = prev_available + mps_value - forecast_value
            
            # Insert new MPS record
            conn.execute(
                text("""
                    INSERT INTO master_production_schedules
                    (product_id, forecast_id, year, week, month, forecast_value, mps_value, available, projected_on_hand, is_edited, created_at, updated_at)
                    VALUES (:product_id, :forecast_id, :year, :week, :month, :forecast_value, :mps, :avail, :poh, 0, NOW(), NOW())
                """),
                {
                    "product_id": product_id,
                    "forecast_id": forecast_id,
                    "year": current_year,
                    "week": current_week,
                    "month": current_month,
                    "forecast_value": forecast_value,
                    "mps": mps_value,
                    "avail": available,
                    "poh": projected_on_hand
                }
            )
        
        # Update prev_available and prev_projected_on_hand for next iteration
        prev_available = available
        prev_projected_on_hand = projected_on_hand
    
    print(f"Created/Updated {len(forecast_data)} MPS records")
