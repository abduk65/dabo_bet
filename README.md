# Bakery ERP System

Complete end-to-end management system for Ethiopian bakery operations with multiple retail branches and central production facility.

## Project Status: Phases 1-4 Complete (Foundation → Financials)

### Implemented Features

#### Foundation Layer
- **Users**: Multi-role authentication (Owner, Manager, Supervisor, Employee)
- **Branches**: Main production + retail branches
- **Units**: Measurement system (weight, volume, count)

#### Phase 1: Inventory Management ✓
- **Material Types**: Categorized raw materials (flour, sugar, oil, etc.) with Amharic translation support
- **Brands**: Brand management for multi-brand inventory
- **Inventory Items**: Brand-specific SKU system with auto-generation
- **Purchase Prices**: Temporal pricing with effective date ranges
- **Purchase Orders**: Full procurement workflow (draft → submitted → received)
- **Inventory Transactions**: Complete audit trail of all stock movements
- **Inventory Adjustments**: Error correction system with approval workflow

#### Phase 2: Production Management ✓
- **Products**: Finished goods with type classification (Bread, Cake, Others)
- **Product Prices**: Temporal pricing for finished goods
- **Recipes**: Production formulas with auto-generated codes (RCP-PRODUCTNAME-001)
- **Recipe Components**: Brand-specific ingredient lists
- **Standard Batch Varieties**: Batch scaling configuration
- **Production Orders**: Work orders with auto-generated numbers (WO-YYYYMMDD-001)
- **Production Material Consumption**: Material usage tracking with variance detection
- **Production Output**: Quality control tracking (good/rejected quantities)
- **Recipe Substitutions**: Brand substitution audit trail
- **Daily Production Adjustments**: Waste tracking (stale/damaged/worker error)
- **Finished Goods Inventory**: Product movement ledger

#### Phase 3: Sales & Distribution ✓
- **Customers**: Walk-in, commission recipients, and branch customers
- **Customer Pricing**: Contract pricing for commission recipients (temporal pattern)
- **Dispatches**: Branch-to-branch transfers with damage tracking
- **Dispatch Items**: Line-level cost snapshots and variance detection
- **Sales**: Cash and credit sales with auto-generated numbers (SALE-BRANCHID-YYYYMMDD-001)
- **Sale Items**: Line-level profit calculation
- **Payments**: Payment tracking with advance payment support

#### Phase 4: Financial Management ✓
- **Chart of Accounts**: Hierarchical account structure (27 system accounts)
- **Journal Entries**: Double-entry bookkeeping with auto-generated numbers (JE-YYYYMMDD-001)
- **Journal Entry Lines**: Debit/credit lines with balance validation
- **Account Balances**: Current and historical balance calculations
- **Entry Posting**: Draft → Posted → Reversed workflow
- **Financial Reports**: Balance sheet and P&L foundations

### Database Schema (42 Tables)

**Foundation Tables (3):**
- `units` - Measurement units
- `branches` - Organizational structure
- `users` - Authentication and roles

**Phase 1: Inventory Tables (8):**
- `material_types` - Generic material categories
- `brands` - Manufacturer brands
- `inventory_items` - Specific brand+material combinations
- `purchase_prices` - Historical price tracking
- `purchase_orders` + `purchase_order_items` - Procurement
- `inventory_transactions` - Stock movement ledger
- `inventory_adjustments` - Stock corrections

**Phase 2: Production Tables (11):**
- `products` - Finished goods catalog
- `product_prices` - Product pricing history
- `recipes` - Production formulas
- `recipe_components` - Brand-specific ingredient lists
- `standard_batch_varieties` - Batch size configurations
- `production_orders` - Work orders
- `production_material_consumption` - Material usage with variance
- `production_output` - Finished goods yield
- `recipe_substitutions` - Brand swap tracking
- `daily_production_adjustments` - Production waste
- `finished_goods_inventory` - Product movement ledger

**Phase 3: Sales & Distribution Tables (7):**
- `customers` - Customer master (walk-in, commission recipients, branches)
- `customer_pricing` - Contract pricing
- `dispatches` - Branch transfers
- `dispatch_items` - Dispatch line items
- `sales` - Sales transactions
- `sale_items` - Sale line items with profit calculation
- `payments` - Payment tracking

**Phase 4: Financial Tables (3):**
- `accounts` - Chart of accounts
- `journal_entries` - Financial transaction headers
- `journal_entry_lines` - Double-entry lines

### Key Design Decisions

1. **Brand-Specific Recipes**: Recipes reference exact brand+material combinations (not generic materials) to support:
   - Multi-brand flour blending (e.g., 60% Brand A + 40% Brand B)
   - Accurate cost tracking per brand
   - Quality traceability

2. **Temporal Pricing**: All prices have effective_date/expiry_date to handle Ethiopian price volatility
   - Applies to: purchase_prices, product_prices, customer_pricing
   - NULL expiry_date = current pricing

3. **Cost Snapshot Pattern**: Unit costs are locked at transaction time
   - `dispatch_items.unit_cost` - COGS at dispatch time
   - `sale_items.unit_cost` - COGS at sale time
   - `production_material_consumption.unit_cost` - Price at production time
   - **Critical**: Prevents retroactive P&L changes when new batches are produced

4. **Auto-Generated Codes**: Consistent format across all entities
   - SKUs: `FLOUR-AP-MAMA-001`
   - Recipes: `RCP-PRODUCTNAME-001`
   - Work Orders: `WO-YYYYMMDD-001`
   - Sales: `SALE-BRANCHID-YYYYMMDD-001`
   - Journal Entries: `JE-YYYYMMDD-001`

5. **Approval Workflows**: Status enums (draft/submitted/approved) prevent premature data propagation

6. **Double-Entry Bookkeeping**: All financial transactions create balanced journal entries
   - Debits must equal credits
   - Polymorphic references to source transactions
   - Posting and reversal support

7. **Variance Detection**: Actual vs. planned tracking
   - Production material consumption variance
   - Dispatch damage/loss tracking
   - Quality control rejection tracking

### API Endpoints (Planned)

**Authentication:**
- POST `/api/login`
- POST `/api/logout`
- GET `/api/user`

**Phase 1: Inventory:**
- GET/POST `/api/inventory/material-types`
- GET/POST `/api/inventory/brands`
- GET/POST `/api/inventory/items`
- GET `/api/inventory/items/{id}/price-history`
- GET/POST `/api/inventory/purchase-orders`
- POST `/api/inventory/purchase-orders/{id}/submit`
- POST `/api/inventory/purchase-orders/{id}/receive`
- GET/POST `/api/inventory/adjustments`
- POST `/api/inventory/adjustments/{id}/approve`

**Phase 2: Production:**
- GET/POST `/api/production/products`
- GET/POST `/api/production/recipes`
- GET `/api/production/recipes/{id}/components`
- POST `/api/production/recipes/{id}/calculate-cost`
- GET/POST `/api/production/orders`
- POST `/api/production/orders/{id}/start`
- POST `/api/production/orders/{id}/record-consumption`
- POST `/api/production/orders/{id}/record-output`
- POST `/api/production/orders/{id}/complete`
- GET/POST `/api/production/adjustments`

**Phase 3: Sales & Distribution:**
- GET/POST `/api/customers`
- GET/POST `/api/customers/{id}/pricing`
- GET/POST `/api/dispatches`
- POST `/api/dispatches/{id}/dispatch`
- POST `/api/dispatches/{id}/receive`
- GET/POST `/api/sales`
- POST `/api/sales/{id}/complete`
- GET/POST `/api/payments`

**Phase 4: Financials:**
- GET `/api/accounts`
- GET `/api/accounts/{id}/balance`
- GET/POST `/api/journal-entries`
- POST `/api/journal-entries/{id}/post`
- POST `/api/journal-entries/{id}/reverse`
- GET `/api/reports/balance-sheet`
- GET `/api/reports/profit-loss`

**Reference Data:**
- GET `/api/units`
- GET `/api/branches`

### Model Relationships

**Key Relationships:**

**InventoryItem** (Phase 1 core):
- belongs to: MaterialType, Brand, Unit
- has many: PurchasePrices, InventoryTransactions, RecipeComponents

**Recipe** (Phase 2 core):
- belongs to: Product, Unit
- has many: RecipeComponents, ProductionOrders
- **Critical**: Components reference inventory_item_id (brand-specific)

**ProductionOrder** (Phase 2):
- belongs to: Recipe, User (supervisor)
- has many: MaterialConsumption, Output, Substitutions
- Auto-creates planned material consumption from recipe

**Sale** (Phase 3 core):
- belongs to: Customer, Branch, User
- has many: SaleItems, Payments
- Auto-generates inventory transactions on completion

**JournalEntry** (Phase 4 core):
- has many: JournalEntryLines
- belongs to: User (creator, poster)
- Polymorphic reference to source transactions
- Enforces debit = credit balance

### Next Steps: Phase 5 - Integration & API Controllers

**Remaining Work:**
1. **API Controllers**: Implement RESTful controllers for all resources
2. **Journal Entry Automation**: Auto-create journal entries for:
   - Purchase order receipt → Inventory asset + Accounts payable
   - Production completion → COGS + Finished goods
   - Dispatch → Finished goods transfer between branches
   - Sale → Revenue + COGS + Cash/Receivable
   - Payment receipt → Cash/Bank + Accounts receivable
3. **Seeders**: Material types, brands, products, customers
4. **Validation Rules**: Form request validation for all endpoints
5. **Authorization**: Policy-based access control per user role
6. **Frontend Integration**: Vue 3 application (separate repository)
7. **Reporting**: Dashboard, P&L, Balance Sheet, Inventory Reports

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
