from fetch_data import fetch_income_data
from forecast import run_forecast
from save_forecast import save_forecast
from calculate_mrp import calculate_mrp_for_all_components
from db import engine
from sqlalchemy import text
import pandas as pd

def get_all_product_ids():
    """Fetch all product IDs from the products table."""
    query = "SELECT id FROM products ORDER BY id"
    df = pd.read_sql(query, engine)
    return df['id'].tolist()

def process_product(product_id, conn):
    """Process forecast for a single product within a transaction."""
    print(f"\n{'='*60}")
    print(f"Processing Product ID: {product_id}")
    print(f"{'='*60}")
    
    df = fetch_income_data(product_id)

    if df.empty:
        print(f"⚠️  No income data found for product_id: {product_id}")
        return False

    print(f"✓ Found {len(df)} income records")
    print(f"✓ Date range: {df['date_received'].min()} to {df['date_received'].max()}")
    print(f"✓ Total quantity: {df['total_quantity'].sum()}")

    try:
        result = run_forecast(df, method='multiplicative', forecast_periods=12)
        
        if 'forecast' in result.columns:
            forecast_data = result[result['forecast'].notna()]
            if not forecast_data.empty:
                print(f"\n✓ Generated forecast for next 12 weeks")
                print(forecast_data[['forecast', 'lower_bound', 'upper_bound']].head(5))
                
                print(f"\n✓ Saving forecast and MPS to database...")
                save_forecast(product_id, result, conn)
                return True
        
        print(f"⚠️  No forecast generated for product_id: {product_id}")
        return False
    
    except Exception as e:
        print(f"❌ Error during forecasting for product_id {product_id}: {str(e)}")
        raise  # Re-raise to trigger rollback

def main():
    print("Starting batch forecast process for all products...")
    print("="*60)
    
    # Get all product IDs
    product_ids = get_all_product_ids()
    print(f"\nFound {len(product_ids)} products in database")
    
    success_count = 0
    failed_count = 0
    skipped_count = 0
    
    # Start a transaction for all operations
    with engine.begin() as conn:
        try:
            # Process each product
            for product_id in product_ids:
                result = process_product(product_id, conn)
                if result is True:
                    success_count += 1
                elif result is False and result is not None:
                    failed_count += 1
                else:
                    skipped_count += 1
            
            # Calculate MRP for all components after all MPS are generated
            if success_count > 0:
                print(f"\n{'='*60}")
                print("STARTING MRP CALCULATION")
                print(f"{'='*60}")
                calculate_mrp_for_all_components(conn)
                print(f"\n✓ MRP calculation completed successfully")
            else:
                print(f"\n⚠️  Skipping MRP calculation - no successful forecasts generated")
            
            # If we reach here, commit the transaction
            print(f"\n{'='*60}")
            print("✓ COMMITTING ALL CHANGES TO DATABASE")
            print(f"{'='*60}")
            
        except Exception as e:
            print(f"\n{'='*60}")
            print("❌ ERROR - ROLLING BACK ALL CHANGES")
            print(f"{'='*60}")
            print(f"Error: {str(e)}")
            raise  # Re-raise to trigger rollback
    
    # Summary (outside transaction block, after commit)
    print(f"\n{'='*60}")
    print("FORECAST SUMMARY")
    print(f"{'='*60}")
    print(f"✓ Successful: {success_count}")
    print(f"❌ Failed: {failed_count}")
    print(f"⚠️  Skipped (no data): {skipped_count}")
    print(f"Total: {len(product_ids)}")
    print(f"{'='*60}")

if __name__ == "__main__":
    main()
