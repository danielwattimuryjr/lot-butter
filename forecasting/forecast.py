import pandas as pd
import numpy as np
from statsmodels.tsa.seasonal import seasonal_decompose
from statsmodels.tsa.holtwinters import ExponentialSmoothing

def run_forecast(df: pd.DataFrame, method='additive', forecast_periods=12):
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

    # seasonal period mingguan (1 tahun ≈ 52)
    # pastikan period tidak lebih dari setengah data
    period = min(52, len(weekly) // 2)
    
    if period < 2:
        raise ValueError(f"Data tidak cukup untuk seasonal decomposition. Diperlukan minimal {2*2} minggu.")

    # Decompose time series
    decomposition = seasonal_decompose(
        weekly,
        model=method,
        period=period,
        extrapolate_trend='freq'  # handle NaN values at edges
    )

    # Build result dataframe with decomposition
    result = pd.DataFrame({
        'actual': weekly,
        'trend': decomposition.trend,
        'seasonal': decomposition.seasonal,
        'residual': decomposition.resid
    })

    # Add forecast using Exponential Smoothing (Holt-Winters)
    try:
        # Remove zeros for better model fitting if using multiplicative
        if method == 'multiplicative':
            weekly_clean = weekly.replace(0, np.nan).interpolate(method='linear').fillna(0.01)
        else:
            weekly_clean = weekly
        
        # Fit Holt-Winters model
        model = ExponentialSmoothing(
            weekly_clean,
            seasonal_periods=period,
            trend='add',
            seasonal=method[:3], 
            damped_trend=True
        )
        fitted_model = model.fit(optimized=True)
        
        # Generate forecast
        forecast = fitted_model.forecast(steps=forecast_periods)
        
        # Add fitted values to result
        result['fitted'] = fitted_model.fittedvalues
        
        # Create forecast dataframe
        forecast_df = pd.DataFrame({
            'forecast': forecast,
            'lower_bound': forecast * 0.8,  # simple confidence interval
            'upper_bound': forecast * 1.2
        })
        
        # Combine historical and forecast
        result = pd.concat([result, forecast_df])
        
    except Exception as e:
        print(f"Warning: Could not generate forecast: {str(e)}")
        # Continue with just decomposition if forecasting fails
    
    return result
