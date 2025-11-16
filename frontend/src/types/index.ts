export interface User {
  id: number
  name: string
  email: string
  role: 'owner' | 'manager' | 'supervisor' | 'employee'
  branch_id: number | null
  branch?: Branch
  created_at: string
  updated_at: string
}

export interface Branch {
  id: number
  name: string
  type: 'main' | 'sub'
  address?: string
  phone?: string
  is_active: boolean
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface LoginResponse {
  success: boolean
  message: string
  data: {
    user: User
    token: string
  }
}

export interface ApiResponse<T = any> {
  success: boolean
  message?: string
  data: T
}

export interface PaginatedResponse<T> {
  success: boolean
  data: T[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface MaterialType {
  id: number
  type_code: string
  type_name: string
  type_name_am?: string
  category: string
  default_unit_id: number
  description?: string
  is_active: boolean
}

export interface Brand {
  id: number
  brand_name: string
  manufacturer?: string
  country_of_origin?: string
  description?: string
  is_active: boolean
}

export interface InventoryItem {
  id: number
  sku_code: string
  material_type_id: number
  brand_id: number
  unit_id: number
  current_stock_quantity: number
  reorder_level?: number
  storage_location?: string
  is_active: boolean
  material_type?: MaterialType
  brand?: Brand
}

export interface Product {
  id: number
  name: string
  name_am?: string
  type: 'Bread' | 'Cake' | 'Others'
  description?: string
  unit_id: number
  shelf_life_days?: number
  is_active: boolean
}

export interface Customer {
  id: number
  customer_code: string
  name: string
  type: 'walk_in' | 'commission_recipient' | 'branch'
  branch_id?: number
  phone?: string
  email?: string
  address?: string
  tin_number?: string
  credit_limit: number
  credit_period_days: number
  is_active: boolean
}

export interface PurchaseOrder {
  id: number
  po_number: string
  supplier_name: string
  order_date: string
  expected_delivery_date?: string
  received_date?: string
  status: 'draft' | 'submitted' | 'approved' | 'received' | 'cancelled'
  total_amount: number
  notes?: string
  created_by_user_id: number
  items?: PurchaseOrderItem[]
}

export interface PurchaseOrderItem {
  id: number
  purchase_order_id: number
  inventory_item_id: number
  quantity_ordered: number
  quantity_received?: number
  unit_price: number
  total_price: number
  inventory_item?: InventoryItem
}

export interface Sale {
  id: number
  sale_number: string
  branch_id: number
  customer_id: number
  sale_date: string
  payment_type: 'cash' | 'credit' | 'bank_transfer'
  subtotal_amount: number
  tax_amount: number
  total_amount: number
  amount_paid: number
  balance_due: number
  due_date?: string
  status: 'draft' | 'completed' | 'cancelled'
  items?: SaleItem[]
  customer?: Customer
}

export interface SaleItem {
  id: number
  sale_id: number
  product_id: number
  quantity: number
  unit_price: number
  unit_cost: number
  line_total: number
  line_profit: number
  product?: Product
}
