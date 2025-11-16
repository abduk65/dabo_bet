/**
 * Application-wide constants
 */

// User Roles
export const ROLES = {
  OWNER: 'owner',
  MANAGER: 'manager',
  SUPERVISOR: 'supervisor',
  EMPLOYEE: 'employee'
} as const

export type UserRole = typeof ROLES[keyof typeof ROLES]

// Product Types
export const PRODUCT_TYPES = {
  BREAD: 'bread',
  PASTRY: 'pastry',
  CAKE: 'cake',
  OTHER: 'other'
} as const

export type ProductType = typeof PRODUCT_TYPES[keyof typeof PRODUCT_TYPES]

// Customer Types
export const CUSTOMER_TYPES = {
  WALK_IN: 'walk_in',
  RETAIL: 'retail',
  WHOLESALE: 'wholesale',
  DISTRIBUTOR: 'distributor'
} as const

export type CustomerType = typeof CUSTOMER_TYPES[keyof typeof CUSTOMER_TYPES]

// Payment Types
export const PAYMENT_TYPES = {
  CASH: 'cash',
  CREDIT: 'credit',
  BANK_TRANSFER: 'bank_transfer',
  MOBILE_MONEY: 'mobile_money',
  CHECK: 'check'
} as const

export type PaymentType = typeof PAYMENT_TYPES[keyof typeof PAYMENT_TYPES]

// Production Order Status
export const PRODUCTION_STATUS = {
  PLANNED: 'planned',
  IN_PROGRESS: 'in_progress',
  COMPLETED: 'completed',
  CANCELLED: 'cancelled'
} as const

export type ProductionStatus = typeof PRODUCTION_STATUS[keyof typeof PRODUCTION_STATUS]

// Purchase Order Status
export const PURCHASE_ORDER_STATUS = {
  PENDING: 'pending',
  APPROVED: 'approved',
  RECEIVED: 'received',
  PARTIALLY_RECEIVED: 'partially_received',
  CANCELLED: 'cancelled'
} as const

export type PurchaseOrderStatus = typeof PURCHASE_ORDER_STATUS[keyof typeof PURCHASE_ORDER_STATUS]

// Adjustment Types
export const ADJUSTMENT_TYPES = {
  PHYSICAL_COUNT: 'physical_count',
  DAMAGE: 'damage',
  EXPIRED: 'expired',
  LOSS: 'loss',
  FOUND: 'found',
  OTHER: 'other'
} as const

export type AdjustmentType = typeof ADJUSTMENT_TYPES[keyof typeof ADJUSTMENT_TYPES]

// Dispatch Status
export const DISPATCH_STATUS = {
  PENDING: 'pending',
  IN_TRANSIT: 'in_transit',
  DELIVERED: 'delivered',
  CANCELLED: 'cancelled'
} as const

export type DispatchStatus = typeof DISPATCH_STATUS[keyof typeof DISPATCH_STATUS]

// Sale Status
export const SALE_STATUS = {
  DRAFT: 'draft',
  COMPLETED: 'completed',
  CANCELLED: 'cancelled'
} as const

export type SaleStatus = typeof SALE_STATUS[keyof typeof SALE_STATUS]

// Payment Status
export const PAYMENT_STATUS = {
  PENDING: 'pending',
  CONFIRMED: 'confirmed',
  VOIDED: 'voided'
} as const

export type PaymentStatus = typeof PAYMENT_STATUS[keyof typeof PAYMENT_STATUS]

// Journal Entry Status
export const JOURNAL_ENTRY_STATUS = {
  DRAFT: 'draft',
  POSTED: 'posted',
  REVERSED: 'reversed'
} as const

export type JournalEntryStatus = typeof JOURNAL_ENTRY_STATUS[keyof typeof JOURNAL_ENTRY_STATUS]

// Account Types
export const ACCOUNT_TYPES = {
  ASSET: 'asset',
  LIABILITY: 'liability',
  EQUITY: 'equity',
  REVENUE: 'revenue',
  EXPENSE: 'expense'
} as const

export type AccountType = typeof ACCOUNT_TYPES[keyof typeof ACCOUNT_TYPES]

// Quality Grades
export const QUALITY_GRADES = {
  PREMIUM: 'premium',
  STANDARD: 'standard',
  SECONDS: 'seconds'
} as const

export type QualityGrade = typeof QUALITY_GRADES[keyof typeof QUALITY_GRADES]

// Date Formats
export const DATE_FORMATS = {
  SHORT: 'short',
  LONG: 'long',
  FULL: 'full'
} as const

// Pagination
export const DEFAULT_PAGE_SIZE = 20
export const PAGE_SIZE_OPTIONS = [10, 20, 50, 100]

// Currency
export const CURRENCY = 'ETB'
export const LOCALE = 'en-ET'

// File Upload
export const MAX_FILE_SIZE = 5 * 1024 * 1024 // 5MB
export const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/jpg']
export const ALLOWED_DOCUMENT_TYPES = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']

// Validation
export const MIN_PASSWORD_LENGTH = 8
export const MAX_DESCRIPTION_LENGTH = 500
export const MAX_NOTES_LENGTH = 1000

// Touch Targets (for mobile-first design)
export const MIN_TOUCH_TARGET_SIZE = 48 // pixels

// Offline Sync
export const OFFLINE_RETRY_INTERVAL = 30000 // 30 seconds
export const MAX_OFFLINE_QUEUE_SIZE = 100

// Toast Notification Durations
export const TOAST_DURATION = {
  SHORT: 3000,
  MEDIUM: 5000,
  LONG: 8000
} as const

// Status Badge Colors (Tailwind classes)
export const STATUS_COLORS = {
  // General statuses
  pending: 'badge-yellow',
  approved: 'badge-blue',
  completed: 'badge-success',
  cancelled: 'badge-red',
  rejected: 'badge-red',
  voided: 'badge-red',

  // Production
  planned: 'badge-blue',
  in_progress: 'badge-yellow',

  // Purchase Orders
  received: 'badge-success',
  partially_received: 'badge-purple',

  // Dispatch
  in_transit: 'badge-blue',
  delivered: 'badge-success',

  // Sales
  draft: 'badge-gray',

  // Payments
  confirmed: 'badge-success',

  // Journal Entries
  posted: 'badge-success',
  reversed: 'badge-red',

  // Quality
  premium: 'badge-success',
  standard: 'badge-blue',
  seconds: 'badge-yellow'
} as const

// Payment Method Colors
export const PAYMENT_METHOD_COLORS = {
  cash: 'badge-green',
  credit: 'badge-blue',
  bank_transfer: 'badge-purple',
  mobile_money: 'badge-yellow',
  check: 'badge-gray'
} as const

// Account Type Colors
export const ACCOUNT_TYPE_COLORS = {
  asset: 'badge-green',
  liability: 'badge-red',
  equity: 'badge-purple',
  revenue: 'badge-blue',
  expense: 'badge-yellow'
} as const
