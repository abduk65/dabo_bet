import api from './api'
import type { ApiResponse } from '@/types'

export interface Customer {
  id: number
  customer_code: string
  customer_name: string
  customer_type: 'walk_in' | 'retail' | 'wholesale' | 'distributor'
  branch_id?: number
  phone?: string
  email?: string
  address?: string
  credit_limit: number
  payment_terms_days: number
  commission_percentage: number
  receives_commission: boolean
  is_active: boolean
  branch?: any
}

export interface CustomerPricing {
  id: number
  customer_id: number
  product_id: number
  custom_price: number
  effective_date: string
  is_active: boolean
}

export interface Dispatch {
  id: number
  dispatch_number: string
  branch_id: number
  dispatch_date: string
  driver_name?: string
  vehicle_info?: string
  status: 'pending' | 'dispatched' | 'received' | 'cancelled'
  created_by_user_id: number
  received_by_user_id?: number
  items?: DispatchItem[]
}

export interface DispatchItem {
  id: number
  dispatch_id: number
  product_id: number
  quantity_sent: number
  quantity_received?: number
  notes?: string
}

export interface Sale {
  id: number
  sale_number: string
  branch_id: number
  customer_id?: number
  sale_date: string
  payment_type: 'cash' | 'credit' | 'bank_transfer' | 'mobile_money'
  subtotal_amount: number
  tax_amount: number
  total_amount: number
  amount_paid: number
  balance_due: number
  due_date?: string
  status: 'draft' | 'completed' | 'cancelled'
  created_by_user_id: number
  notes?: string
  customer?: Customer
  items?: SaleItem[]
  payments?: Payment[]
}

export interface SaleItem {
  id: number
  sale_id: number
  product_id: number
  quantity: number
  unit_price: number
  discount_percentage: number
  line_total: number
  product?: any
}

export interface Payment {
  id: number
  payment_number: string
  customer_id?: number
  sale_id?: number
  payment_date: string
  amount: number
  payment_method: 'cash' | 'bank_transfer' | 'mobile_money' | 'check'
  payment_type: 'advance' | 'invoice' | 'refund'
  reference_number?: string
  notes?: string
  created_by_user_id: number
}

export const salesService = {
  // Customers
  async getCustomers(params?: { type?: string }): Promise<Customer[]> {
    const response = await api.get<ApiResponse<Customer[]>>('/customers', { params })
    return response.data.data
  },

  async getCustomer(id: number): Promise<Customer> {
    const response = await api.get<ApiResponse<Customer>>(`/customers/${id}`)
    return response.data.data
  },

  async createCustomer(data: Partial<Customer>): Promise<Customer> {
    const response = await api.post<ApiResponse<Customer>>('/customers', data)
    return response.data.data
  },

  async updateCustomer(id: number, data: Partial<Customer>): Promise<Customer> {
    const response = await api.put<ApiResponse<Customer>>(`/customers/${id}`, data)
    return response.data.data
  },

  async deleteCustomer(id: number): Promise<void> {
    await api.delete(`/customers/${id}`)
  },

  async getCustomersByType(type: string): Promise<Customer[]> {
    const response = await api.get<ApiResponse<Customer[]>>(`/customers/type/${type}`)
    return response.data.data
  },

  async getCommissionRecipients(): Promise<Customer[]> {
    const response = await api.get<ApiResponse<Customer[]>>('/customers-commission-recipients')
    return response.data.data
  },

  async getCustomerPricing(customerId: number): Promise<CustomerPricing[]> {
    const response = await api.get<ApiResponse<CustomerPricing[]>>(`/customers/${customerId}/pricing`)
    return response.data.data
  },

  async setCustomerPricing(customerId: number, data: Partial<CustomerPricing>): Promise<CustomerPricing> {
    const response = await api.post<ApiResponse<CustomerPricing>>(`/customers/${customerId}/set-pricing`, data)
    return response.data.data
  },

  async getCustomerOutstandingBalance(customerId: number): Promise<{ balance: number }> {
    const response = await api.get<ApiResponse<{ balance: number }>>(`/customers/${customerId}/outstanding-balance`)
    return response.data.data
  },

  // Dispatches
  async getDispatches(): Promise<Dispatch[]> {
    const response = await api.get<ApiResponse<Dispatch[]>>('/dispatches')
    return response.data.data
  },

  async getDispatch(id: number): Promise<Dispatch> {
    const response = await api.get<ApiResponse<Dispatch>>(`/dispatches/${id}`)
    return response.data.data
  },

  async createDispatch(data: Partial<Dispatch>): Promise<Dispatch> {
    const response = await api.post<ApiResponse<Dispatch>>('/dispatches', data)
    return response.data.data
  },

  async updateDispatch(id: number, data: Partial<Dispatch>): Promise<Dispatch> {
    const response = await api.put<ApiResponse<Dispatch>>(`/dispatches/${id}`, data)
    return response.data.data
  },

  async deleteDispatch(id: number): Promise<void> {
    await api.delete(`/dispatches/${id}`)
  },

  async markDispatched(id: number): Promise<Dispatch> {
    const response = await api.post<ApiResponse<Dispatch>>(`/dispatches/${id}/mark-dispatched`)
    return response.data.data
  },

  async receiveDispatch(id: number, data: { items: { item_id: number; quantity_received: number }[] }): Promise<Dispatch> {
    const response = await api.post<ApiResponse<Dispatch>>(`/dispatches/${id}/receive`, data)
    return response.data.data
  },

  async getPendingDispatches(): Promise<Dispatch[]> {
    const response = await api.get<ApiResponse<Dispatch[]>>('/dispatches-pending')
    return response.data.data
  },

  // Sales
  async getSales(params?: { status?: string }): Promise<Sale[]> {
    const response = await api.get<ApiResponse<Sale[]>>('/sales', { params })
    return response.data.data
  },

  async getSale(id: number): Promise<Sale> {
    const response = await api.get<ApiResponse<Sale>>(`/sales/${id}`)
    return response.data.data
  },

  async createSale(data: Partial<Sale>): Promise<Sale> {
    const response = await api.post<ApiResponse<Sale>>('/sales', data)
    return response.data.data
  },

  async updateSale(id: number, data: Partial<Sale>): Promise<Sale> {
    const response = await api.put<ApiResponse<Sale>>(`/sales/${id}`, data)
    return response.data.data
  },

  async deleteSale(id: number): Promise<void> {
    await api.delete(`/sales/${id}`)
  },

  async completeSale(id: number): Promise<Sale> {
    const response = await api.post<ApiResponse<Sale>>(`/sales/${id}/complete`)
    return response.data.data
  },

  async cancelSale(id: number): Promise<Sale> {
    const response = await api.post<ApiResponse<Sale>>(`/sales/${id}/cancel`)
    return response.data.data
  },

  async getOutstandingSales(): Promise<Sale[]> {
    const response = await api.get<ApiResponse<Sale[]>>('/sales-outstanding')
    return response.data.data
  },

  async getOverdueSales(): Promise<Sale[]> {
    const response = await api.get<ApiResponse<Sale[]>>('/sales-overdue')
    return response.data.data
  },

  // Payments
  async getPayments(): Promise<Payment[]> {
    const response = await api.get<ApiResponse<Payment[]>>('/payments')
    return response.data.data
  },

  async getPayment(id: number): Promise<Payment> {
    const response = await api.get<ApiResponse<Payment>>(`/payments/${id}`)
    return response.data.data
  },

  async createPayment(data: Partial<Payment>): Promise<Payment> {
    const response = await api.post<ApiResponse<Payment>>('/payments', data)
    return response.data.data
  },

  async updatePayment(id: number, data: Partial<Payment>): Promise<Payment> {
    const response = await api.put<ApiResponse<Payment>>(`/payments/${id}`, data)
    return response.data.data
  },

  async deletePayment(id: number): Promise<void> {
    await api.delete(`/payments/${id}`)
  },

  async getAdvancePayments(): Promise<Payment[]> {
    const response = await api.get<ApiResponse<Payment[]>>('/payments-advance')
    return response.data.data
  },

  async getPaymentsByCustomer(customerId: number): Promise<Payment[]> {
    const response = await api.get<ApiResponse<Payment[]>>(`/payments/customer/${customerId}`)
    return response.data.data
  }
}
