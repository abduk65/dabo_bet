# Bakery ERP System

Complete end-to-end management system for Ethiopian bakery operations with multiple retail branches and central production facility.

## Project Status: Phase 1 - Foundation Complete

### Implemented Features

#### Foundation Layer
- **Users**: Multi-role authentication (Owner, Manager, Supervisor, Employee)
- **Branches**: Main production + retail branches
- **Units**: Measurement system (weight, volume, count)

#### Phase 1: Inventory Management
- **Material Types**: Categorized raw materials (flour, sugar, oil, etc.) with Amharic translation support
- **Brands**: Brand management for multi-brand inventory
- **Inventory Items**: Brand-specific SKU system with auto-generation
- **Purchase Prices**: Temporal pricing with effective date ranges
- **Purchase Orders**: Full procurement workflow (draft → submitted → received)
- **Inventory Transactions**: Complete audit trail of all stock movements
- **Inventory Adjustments**: Error correction system with approval workflow

### Database Schema

**Foundation Tables:**
- `units` - Measurement units
- `branches` - Organizational structure
- `users` - Authentication and roles

**Inventory Tables:**
- `material_types` - Generic material categories
- `brands` - Manufacturer brands
- `inventory_items` - Specific brand+material combinations
- `purchase_prices` - Historical price tracking
- `purchase_orders` + `purchase_order_items` - Procurement
- `inventory_transactions` - Stock movement ledger
- `inventory_adjustments` - Stock corrections

### Key Design Decisions

1. **Brand-Specific Recipes**: Recipes reference exact brand+material combinations (not generic materials) to support:
   - Multi-brand flour blending (e.g., 60% Brand A + 40% Brand B)
   - Accurate cost tracking per brand
   - Quality traceability

2. **Temporal Pricing**: All prices have effective_date/expiry_date to handle Ethiopian price volatility

3. **Auto-Generated SKUs**: Format `{MATERIAL_TYPE}-{BRAND}-{SEQ}` (e.g., `FLOUR-AP-MAMA-001`)

4. **Approval Workflows**: Status enums (draft/submitted/approved) prevent premature data propagation

### API Endpoints

**Authentication:**
- POST `/api/login`
- POST `/api/logout`
- GET `/api/user`

**Inventory:**
- GET/POST `/api/inventory/material-types`
- GET/POST `/api/inventory/brands`
- GET/POST `/api/inventory/items`
- GET `/api/inventory/items/{id}/price-history`
- GET/POST `/api/inventory/purchase-orders`
- POST `/api/inventory/purchase-orders/{id}/submit`
- POST `/api/inventory/purchase-orders/{id}/receive`
- GET/POST `/api/inventory/adjustments`
- POST `/api/inventory/adjustments/{id}/approve`

**Reference Data:**
- GET `/api/units`
- GET `/api/branches`

### Model Relationships

**InventoryItem** (core entity):
- belongs to: MaterialType, Brand, Unit
- has many: PurchasePrices, InventoryTransactions, PurchaseOrderItems

**PurchaseOrder**:
- has many: PurchaseOrderItems
- belongs to: User (creator, approver, receiver)

**User**:
- belongs to: Branch
- has many: created entities (scoped by role)

### Next Steps: Phase 2 - Production

**Upcoming Tables:**
- `products` - Finished goods
- `recipes` + `recipe_components` - Production formulas
- `production_orders` - Work orders
- `production_material_consumption` - Material usage tracking
- `production_output` - Finished goods yield
- `recipe_substitutions` - Brand swap tracking

### Development Setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Run migrations and seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. Default credentials:
   - Owner: owner@bakery.com / password
   - Manager: manager@bakery.com / password
   - Supervisor: supervisor@bakery.com / password
   - Employee: employee@bakery.com / password

### Technology Stack

- **Backend**: Laravel 10.x (API-only, no Blade)
- **Authentication**: Laravel Sanctum
- **Database**: MySQL
- **Frontend**: Vue 3 (separate implementation)

### Architecture Principles

1. **API-First**: Pure JSON API, no server-side rendering
2. **Phased Development**: Inventory → Production → Sales → Financials
3. **Audit Everything**: All changes logged via Laravel events
4. **Role-Based Access**: Granular permissions per user role
5. **Temporal Data**: Historical accuracy for volatile pricing

### Business Context

**Ethiopian Bakery Operations:**
- Central production facility (main branch)
- Multiple retail locations (sub branches)
- Price volatility requires historical tracking
- Low-literacy workforce needs simple, validated interfaces
- Commission recipients = bulk buyers with negotiated pricing
- Daily reconciliation critical for cash control

**Success Metrics:**
- Daily sales reconciliation: 40min → 20min (target 50% reduction)
- Cash variance: <3% threshold
- Production cost accuracy: ±5 ETB per batch
- Real-time bank balance visibility for owner
