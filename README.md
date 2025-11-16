<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Status: Phases 1-6 Complete (Foundation → Production Ready)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

#### Phase 5: API Controllers & Routes ✓
- **16 RESTful API Controllers**: Complete CRUD operations for all resources
- **100+ API Endpoints**: Organized by phase with clear routing structure
- **Authentication**: Login/logout, password management via Laravel Sanctum
- **Workflow Management**: State validation for all approval processes
- **Consistent Response Format**: Standardized JSON responses across all endpoints
- **Error Handling**: Comprehensive validation and error messages
- **Performance**: Eager loading, database transactions, optimized queries

#### Phase 6: Validation, Authorization & Test Data ✓
- **Form Request Validation**: 4 dedicated request classes with advanced validation
- **Authorization Policies**: Role-based access control for key resources
- **Test Data Seeders**: Realistic Ethiopian bakery data (materials, products, customers)
- **Multi-Role Security**: Owner → Manager → Supervisor → Employee hierarchy
- **Business Rule Validation**: Credit limits, balanced entries, workflow states

### Database Schema (42 Tables)

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

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

### Project Statistics

**Backend Implementation:**
- 32 Eloquent Models with full relationships
- 32 Database Migrations (42 tables)
- 16 RESTful API Controllers
- 100+ API Endpoints
- 9 Seeders (with realistic test data)
- 4 Form Request Validation Classes
- 4 Authorization Policies
- Complete Laravel scaffolding

**Test Data Included:**
- 19 Material Types (Ethiopian bakery context)
- 14 Brands (local and international)
- 15 Products (bread, cakes, pastries)
- 13 Customers (hotels, restaurants, institutions)
- 27 Chart of Accounts entries
- 4 Test users (owner, manager, supervisor, employee)
- 3 Branches (main production + 2 retail)

### Next Steps: Optional Enhancements

**Completed:** ✅ Phase 1-6 (Foundation through Production-Ready API)

**Optional Additions:**
1. **Journal Entry Automation**: Auto-create journal entries for:
   - Purchase order receipt → Inventory asset + Accounts payable
   - Production completion → COGS + Finished goods
   - Dispatch → Finished goods transfer between branches
   - Sale → Revenue + COGS + Cash/Receivable
   - Payment receipt → Cash/Bank + Accounts receivable

2. **Additional Form Requests**: Extract validation for remaining controllers

3. **Additional Policies**: Expand authorization to all resources

4. **API Tests**: PHPUnit/Pest tests for all endpoints

5. **Frontend Application**: Vue 3 SPA (separate repository)

6. **Advanced Reporting**:
   - Real-time dashboard with KPIs
   - P&L Statement by period
   - Balance Sheet
   - Inventory valuation reports
   - Production efficiency analytics
   - Sales by customer/product/branch
   - Cash flow statement

7. **Excel Export/Import**: Bulk data operations

8. **Email Notifications**: Low stock alerts, pending approvals

9. **Mobile App**: React Native for field operations

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
