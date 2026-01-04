import pandas as pd
import numpy as np

def run_forecast(df: pd.DataFrame, method='multiplicative', forecast_periods=12):
    """
    Run time series forecasting on income data using linear regression.
    
    Args:
        df: DataFrame with date_received and total_quantity columns
        method: 'additive' or 'multiplicative' for seasonal decomposition
        forecast_periods: number of weeks to forecast into the future
    
    Returns:
        DataFrame with actual values, decomposition, and forecasts
    """
    # pastikan datetime
    df['date_received'] = pd.to_datetime(df['date_received'])

    # set index waktu
    df = df.set_index('date_received')

    # resample ke mingguan, fill missing dates with 0
    weekly = df['total_quantity'].resample('W').sum().fillna(0)

    # VALIDASI DATA
    if len(weekly) < 4:
        raise ValueError(f"Data mingguan terlalu sedikit untuk forecast. Diperlukan minimal 4 minggu, tersedia {len(weekly)} minggu.")

    # Manual Seasonal Index - 4 minggu per bulan, 48 minggu per tahun
    # Bulan 4 (April), 8 (Agustus), 12 (Desember) = 1.5
    # Bulan lainnya = 1.0
    def get_seasonal_index(week_number):
        # Hitung bulan berdasarkan week (1-48 per tahun, reset setiap 48 minggu)
        # Week 1-4 = Bulan 1, Week 5-8 = Bulan 2, ..., Week 45-48 = Bulan 12
        month_in_year = ((week_number - 1) % 48) // 4 + 1
        
        if month_in_year in [4, 8, 12]:  # April, Agustus, Desember
            return 1.5
        else:
            return 1.0
    
    # Prepare data for linear regression
    weekly_df = weekly.to_frame('quantity')
    
    # Create continuous week numbers (tidak reset per tahun)
    # Week 1 = minggu pertama data, increment terus tanpa reset
    weekly_df['week_number'] = range(1, len(weekly) + 1)  # For regression: 1, 2, 3, ..., n
    
    # Calculate seasonal index based on week number (4 weeks per month)
    weekly_df['seasonal'] = weekly_df['week_number'].map(get_seasonal_index)
    
    # Calculate month for display (1-12, repeating every 48 weeks)
    weekly_df['month_in_year'] = ((weekly_df['week_number'] - 1) % 48) // 4 + 1
    
    # Calculate INTERCEPT and SLOPE using numpy (equivalent to Excel INTERCEPT and SLOPE)
    X = weekly_df['week_number'].values  # Week 1-40
    y = weekly_df['quantity'].values      # Penjualan per minggu
    
    # Using numpy polyfit for linear regression (degree=1 for linear)
    # Returns [slope, intercept]
    coefficients = np.polyfit(X, y, 1)
    slope = coefficients[0]
    intercept = coefficients[1]
    
    print(f"\n{'='*60}")
    print("STEP 1 & 2: LINEAR REGRESSION PARAMETERS")
    print(f"{'='*60}")
    print(f"Data: {len(X)} weeks (week 1 to {len(X)})")
    print(f"INTERCEPT (Konstanta): {intercept:.4f}")
    print(f"SLOPE (Kemiringan): {slope:.4f}")
    print(f"\nFormula: Trend = {intercept:.4f} + ({slope:.4f} × Week)")
    
    # Calculate trend for each week: intercept + (slope * week_number)
    weekly_df['trend'] = intercept + (slope * weekly_df['week_number'])
    
    # Calculate residual
    if method == 'multiplicative':
        weekly_df['residual'] = weekly_df['quantity'] / (weekly_df['trend'] * weekly_df['seasonal'])
    else:
        weekly_df['residual'] = weekly_df['quantity'] - (weekly_df['trend'] + weekly_df['seasonal'])
    
    # Calculate fitted values for historical data
    if method == 'multiplicative':
        weekly_df['fitted'] = weekly_df['trend'] * weekly_df['seasonal']
    else:
        weekly_df['fitted'] = weekly_df['trend'] + weekly_df['seasonal']
    
    # Debug: Print seasonal index distribution
    print(f"\nSeasonal Index Summary (4 weeks per month, 48 weeks per year):")
    print(f"  - Total weeks in historical data: {len(weekly_df)}")
    print(f"  - Week number range: {weekly_df['week_number'].min()} to {weekly_df['week_number'].max()}")
    print(f"  - Month range: {weekly_df['month_in_year'].min()} to {weekly_df['month_in_year'].max()}")
    print(f"  - Weeks with seasonal index 1.5: {(weekly_df['seasonal'] == 1.5).sum()}")
    print(f"  - Weeks with seasonal index 1.0: {(weekly_df['seasonal'] == 1.0).sum()}")
    
    # Show which months have 1.5
    months_with_15 = weekly_df[weekly_df['seasonal'] == 1.5]['month_in_year'].unique()
    if len(months_with_15) > 0:
        print(f"  - Months with seasonal index 1.5: {sorted(months_with_15.tolist())}")
    
    # Build result dataframe
    result = pd.DataFrame({
        'actual': weekly_df['quantity'],
        'trend': weekly_df['trend'],
        'seasonal': weekly_df['seasonal'],
        'residual': weekly_df['residual'],
        'fitted': weekly_df['fitted']
    })

    # Generate forecast for future weeks
    try:
        # Get last week number
        last_week_number = len(weekly)
        
        # Generate future dates
        last_date = weekly.index[-1]
        future_dates = pd.date_range(start=last_date + pd.Timedelta(weeks=1), periods=forecast_periods, freq='W')
        
        # Calculate forecast for each future week
        forecast_values = []
        trend_values = []
        seasonal_indices = []
        continuous_weeks = []
        
        print(f"\n{'='*60}")
        print("STEP 3, 4, 5: FORECAST CALCULATION")
        print(f"{'='*60}")
        print(f"Last historical week: {last_week_number}")
        print(f"Forecast starts from week: {last_week_number + 1}")
        print(f"Calendar system: 4 weeks/month, 48 weeks/year\n")
        print(f"{'Week':<6} {'Month':<7} {'Date':<12} {'Trend':<12} {'Seasonal':<10} {'Forecast':<12}")
        print(f"{'-'*6} {'-'*7} {'-'*12} {'-'*12} {'-'*10} {'-'*12}")
        
        for i, future_date in enumerate(future_dates):
            # Week number for forecast: 41, 42, 43, ... (start dari 41 karena historical 40)
            week_number = last_week_number + i + 1
            
            # Calculate month (1-12, repeating every 48 weeks)
            month_num = ((week_number - 1) % 48) // 4 + 1
            
            # STEP 3: Calculate trend = konstanta + (kemiringan × week_number)
            trend_value = intercept + (slope * week_number)
            
            # STEP 4: Apply seasonal index based on week_number (not date)
            seasonal_idx = get_seasonal_index(week_number)
            
            # STEP 5: Forecast = Trend × Seasonal Index
            forecast_value = trend_value * seasonal_idx
            
            forecast_values.append(max(0, forecast_value))  # Ensure non-negative
            trend_values.append(trend_value)
            seasonal_indices.append(seasonal_idx)
            continuous_weeks.append(week_number)
            
            # Print detail untuk setiap minggu
            print(f"{week_number:<6} {month_num:<7} {future_date.strftime('%Y-%m-%d'):<12} {trend_value:<12.2f} {seasonal_idx:<10.1f} {forecast_value:<12.2f}")
        
        # Create forecast dataframe
        forecast_df = pd.DataFrame({
            'forecast': forecast_values,
            'trend': trend_values,
            'seasonal': seasonal_indices,
            'continuous_week': continuous_weeks,
            'lower_bound': [f * 0.8 for f in forecast_values],
            'upper_bound': [f * 1.2 for f in forecast_values]
        }, index=future_dates)
        
        # Combine historical and forecast
        result = pd.concat([result, forecast_df])
        
        # Store intercept and slope as metadata
        result.intercept = intercept
        result.slope = slope
        
        print(f"\n{'='*60}")
        print(f"SUMMARY: Generated {len(forecast_values)} forecast weeks")
        print(f"{'='*60}")
        
    except Exception as e:
        print(f"Warning: Could not generate forecast: {str(e)}")
        import traceback
        traceback.print_exc()
    
    return result
