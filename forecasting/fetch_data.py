import pandas as pd
from db import engine

def fetch_income_data(product_id: int):
    query = f"""
        SELECT 
            date_received,
            SUM(quantity) AS total_quantity
        FROM incomes
        WHERE product_id = {int(product_id)}
        GROUP BY date_received
        ORDER BY date_received
    """

    return pd.read_sql(query, engine)
