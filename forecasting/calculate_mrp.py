from sqlalchemy import text
from db import engine
import pandas as pd

def calculate_mrp_for_all_components(conn=None):
    """
    Calculate Material Requirements Planning for all components based on MPS data.
    This should be called after MPS records are successfully saved.
    
    Args:
        conn: Database connection (optional, will create new transaction if None)
    """
    if conn is None:
        # If no connection provided, create a new transaction
        with engine.begin() as conn:
            _calculate_mrp_internal(conn)
    else:
        # Use provided connection (part of larger transaction)
        _calculate_mrp_internal(conn)

def _calculate_mrp_internal(conn):
    """
    Internal function to calculate MRP using provided connection.
    """
    # Get all unique components
    components = conn.execute(
        text("SELECT id FROM components ORDER BY id")
    ).fetchall()
    
    for component_row in components:
        component_id = component_row[0]
        print(f"\n{'='*60}")
        print(f"Calculating MRP for Component ID: {component_id}")
        print(f"{'='*60}")
        calculate_mrp_for_component(conn, component_id)
    
    print(f"\n{'='*60}")
    print("MRP Calculation Complete for All Components")
    print(f"{'='*60}")

def calculate_mrp_for_component(conn, component_id):
    """
    Calculate MRP for a specific component.
    
    Args:
        conn: Database connection
        component_id: The component ID
    """
    # Delete existing MRP records for this component
    conn.execute(
        text("DELETE FROM material_requirements_plannings WHERE component_id = :component_id"),
        {"component_id": component_id}
    )
    
    # Get all products that use this component with their BOM quantities
    # Group by identical BOM (same component_id and quantity) to avoid counting duplicate BOMs
    products_using_component = conn.execute(
        text("""
            SELECT DISTINCT bom.component_id, bom.quantity
            FROM bills_of_materials bom
            WHERE bom.component_id = :component_id
            GROUP BY bom.component_id, bom.quantity
            LIMIT 1
        """),
        {"component_id": component_id}
    ).fetchone()
    
    if not products_using_component:
        print(f"  ⚠ Component {component_id} is not used in any BOM. Skipping.")
        return
    
    bom_quantity = float(products_using_component[1])
    
    # Get all MPS records grouped by year, week, month
    # Calculate gross requirements: sum of (MPS * BOM quantity) for products using this component
    mps_weeks = conn.execute(
        text("""
            SELECT 
                mps.year,
                mps.week,
                mps.month,
                SUM(mps.mps_value * bom.quantity) as total_gross_requirement
            FROM master_production_schedules mps
            INNER JOIN bills_of_materials bom ON mps.product_id = bom.product_id
            WHERE bom.component_id = :component_id
                AND mps.mps_value IS NOT NULL
                AND mps.forecast_id IS NOT NULL
            GROUP BY mps.year, mps.week, mps.month
            ORDER BY mps.year, mps.month, mps.week
        """),
        {"component_id": component_id}
    ).fetchall()
    
    if not mps_weeks:
        print(f"  ⚠ No MPS data found for products using component {component_id}. Skipping.")
        return
    
    # Track previous week's projected on hand and current month
    prev_projected_on_hand = 0
    prev_month = None
    first_week_tracker = {}  # Track first week insertion for each month
    
    for week_data in mps_weeks:
        year = week_data[0]
        week = week_data[1]
        month = week_data[2]
        gross_requirement = int(week_data[3]) if week_data[3] else 0
        
        # Check if this is the first week of a new month
        if month != prev_month:
            # Insert a "First Stock" row (week 0) before the first week of the month
            if month not in first_week_tracker:
                conn.execute(
                    text("""
                        INSERT INTO material_requirements_plannings
                        (component_id, year, week, month, gross_requirements, schedule_receipts, 
                         projected_on_hand, net_requirements, planned_order_receipts, planned_order_releases,
                         created_at, updated_at)
                        VALUES (:component_id, :year, 0, :month, NULL, NULL, 0, NULL, NULL, NULL, NOW(), NOW())
                    """),
                    {
                        "component_id": component_id,
                        "year": year,
                        "month": month
                    }
                )
                first_week_tracker[month] = True
                print(f"  ✓ Inserted 'First Stock' row for year {year}, month {month}")
                prev_projected_on_hand = 0
            
            prev_month = month
        
        # Scheduled receipts: always 0 (can be updated by user later)
        schedule_receipts = 0
        
        # Planned order receipts/releases: always 0 initially (user can input)
        planned_order_receipts = 0
        planned_order_releases = 0
        
        # Net requirements: gross requirement - (POH last week + scheduled receipts)
        net_requirements = gross_requirement - (prev_projected_on_hand + schedule_receipts)
        net_requirements = max(0, net_requirements)  # Cannot be negative
        
        # Projected on Hand: POH last week + scheduled receipts + planned order receipts - gross requirement
        projected_on_hand = prev_projected_on_hand + schedule_receipts + planned_order_receipts - gross_requirement
        
        # Insert MRP record
        conn.execute(
            text("""
                INSERT INTO material_requirements_plannings
                (component_id, year, week, month, gross_requirements, schedule_receipts, 
                 projected_on_hand, net_requirements, planned_order_receipts, planned_order_releases,
                 created_at, updated_at)
                VALUES (:component_id, :year, :week, :month, :gross_req, :sched_rec,
                        :poh, :net_req, :po_rec, :po_rel, NOW(), NOW())
            """),
            {
                "component_id": component_id,
                "year": year,
                "week": week,
                "month": month,
                "gross_req": gross_requirement,
                "sched_rec": schedule_receipts,
                "poh": projected_on_hand,
                "net_req": net_requirements,
                "po_rec": planned_order_receipts,
                "po_rel": planned_order_releases
            }
        )
        
        # Update prev_projected_on_hand for next iteration
        prev_projected_on_hand = projected_on_hand
    
    print(f"  ✓ Created {len(mps_weeks)} MRP records for component {component_id}")

if __name__ == "__main__":
    calculate_mrp_for_all_components()
