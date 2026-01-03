import pandas as pd
import numpy as np

def run_forecast(df: pd.DataFrame, method='multiplicative', forecast_periods=12):
    """
    Run time series forecasting on income data.
    
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
    if len(weekly) < 26:
        raise ValueError(f"Data mingguan terlalu sedikit untuk forecast. Diperlukan minimal 26 minggu, tersedia {len(weekly)} minggu.")

    # Manual Seasonal Index
    # Desember (12), April (4), Agustus (8) = 1.5
    # Bulan lainnya = 1.0
    def get_seasonal_index(date):
        month = date.month
        if month in [4, 8, 12]:  # April, Agustus, Desember
            return 1.5
        else:
            return 1.0
    
    # Apply seasonal index to data
    weekly_df = weekly.to_frame('quantity')
    weekly_df['seasonal'] = weekly_df.index.map(get_seasonal_index)
    
    # Debug: Print seasonal index distribution
    print(f"\nSeasonal Index Summary:")
    print(f"  - Weeks with index 1.5: {(weekly_df['seasonal'] == 1.5).sum()}")
    print(f"  - Weeks with index 1.0: {(weekly_df['seasonal'] == 1.0).sum()}")
    print(f"  - Unique seasonal values: {weekly_df['seasonal'].unique()}")
    
    # Calculate trend using moving average (13 weeks ≈ 1 quarter)
    window = min(13, len(weekly) // 3)
    if window < 3:
        window = 3
    weekly_df['trend'] = weekly_df['quantity'].rolling(window=window, center=True).mean()
    
    # Fill NaN trends at edges with forward/backward fill
    weekly_df['trend'].fillna(method='bfill', inplace=True)
    weekly_df['trend'].fillna(method='ffill', inplace=True)
    
    # Calculate residual
    if method == 'multiplicative':
        weekly_df['residual'] = weekly_df['quantity'] / (weekly_df['trend'] * weekly_df['seasonal'])
    else:
        weekly_df['residual'] = weekly_df['quantity'] - (weekly_df['trend'] + weekly_df['seasonal'])
    
    # Build result dataframe
    result = pd.DataFrame({
        'actual': weekly_df['quantity'],
        'trend': weekly_df['trend'],
        'seasonal': weekly_df['seasonal'],
        'residual': weekly_df['residual']
    })

    # Add forecast using trend projection with manual seasonal index
    try:
        # Get last trend value
        last_trend = weekly_df['trend'].iloc[-1]
        
        # Calculate average trend growth rate
        trend_values = weekly_df['trend'].dropna()
        if len(trend_values) > 1:
            trend_growth = (trend_values.iloc[-1] - trend_values.iloc[0]) / len(trend_values)
        else:
            trend_growth = 0
        
        # Generate future dates
        last_date = weekly.index[-1]
        future_dates = pd.date_range(start=last_date + pd.Timedelta(weeks=1), periods=forecast_periods, freq='W')
        
        # Calculate forecast for each future week
        forecast_values = []
        for i, future_date in enumerate(future_dates):
            # Project trend
            projected_trend = last_trend + (trend_growth * (i + 1))
            
            # Apply seasonal index based on month
            seasonal_idx = get_seasonal_index(future_date)
            
            # Forecast = Trend × Seasonal Index
            if method == 'multiplicative':
                forecast_value = projected_trend * seasonal_idx
            else:
                forecast_value = projected_trend + seasonal_idx
            
            forecast_values.append(max(0, forecast_value))  # Ensure non-negative
        
        # Debug: Show seasonal index for forecast period
        seasonal_indices = [get_seasonal_index(d) for d in future_dates]
        print(f"\nForecast Seasonal Pattern (first 10 weeks):")
        for i in range(min(10, len(future_dates))):
            print(f"  Week {i+1} ({future_dates[i].strftime('%Y-%m-%d')}): seasonal = {seasonal_indices[i]}")
        
        # Create forecast dataframe
        forecast_df = pd.DataFrame({
            'forecast': forecast_values,
            'seasonal': seasonal_indices,
            'lower_bound': [f * 0.8 for f in forecast_values],
            'upper_bound': [f * 1.2 for f in forecast_values]
        }, index=future_dates)
        
        # Calculate fitted values for historical data
        if method == 'multiplicative':
            result['fitted'] = result['trend'] * result['seasonal']
        else:
            result['fitted'] = result['trend'] + result['seasonal']
        
        # Combine historical and forecast
        result = pd.concat([result, forecast_df])
        
    except Exception as e:
        print(f"Warning: Could not generate forecast: {str(e)}")
        # Continue with just decomposition if forecasting fails
    
    return result
