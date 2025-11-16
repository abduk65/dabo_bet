import api from './api'
import type {
  MaterialType,
  Brand,
  InventoryItem,
  PurchaseOrder,
  PurchaseOrderItem,
  ApiResponse,
  PaginatedResponse
} from '@/types'

export const inventoryService = {
  // Material Types
  async getMaterialTypes(): Promise<MaterialType[]> {
    const response = await api.get<ApiResponse<MaterialType[]>>('/inventory/material-types')
    return response.data.data
  },

  async getMaterialType(id: number): Promise<MaterialType> {
    const response = await api.get<ApiResponse<MaterialType>>(`/inventory/material-types/${id}`)
    return response.data.data
  },

  async createMaterialType(data: Partial<MaterialType>): Promise<MaterialType> {
    const response = await api.post<ApiResponse<MaterialType>>('/inventory/material-types', data)
    return response.data.data
  },

  async updateMaterialType(id: number, data: Partial<MaterialType>): Promise<MaterialType> {
    const response = await api.put<ApiResponse<MaterialType>>(`/inventory/material-types/${id}`, data)
    return response.data.data
  },

  async deleteMaterialType(id: number): Promise<void> {
    await api.delete(`/inventory/material-types/${id}`)
  },

  // Brands
  async getBrands(): Promise<Brand[]> {
    const response = await api.get<ApiResponse<Brand[]>>('/inventory/brands')
    return response.data.data
  },

  async getBrand(id: number): Promise<Brand> {
    const response = await api.get<ApiResponse<Brand>>(`/inventory/brands/${id}`)
    return response.data.data
  },

  async createBrand(data: Partial<Brand>): Promise<Brand> {
    const response = await api.post<ApiResponse<Brand>>('/inventory/brands', data)
    return response.data.data
  },

  async updateBrand(id: number, data: Partial<Brand>): Promise<Brand> {
    const response = await api.put<ApiResponse<Brand>>(`/inventory/brands/${id}`, data)
    return response.data.data
  },

  async deleteBrand(id: number): Promise<void> {
    await api.delete(`/inventory/brands/${id}`)
  },

  // Inventory Items
  async getInventoryItems(params?: { search?: string; material_type_id?: number }): Promise<InventoryItem[]> {
    const response = await api.get<ApiResponse<InventoryItem[]>>('/inventory/items', { params })
    return response.data.data
  },

  async getInventoryItem(id: number): Promise<InventoryItem> {
    const response = await api.get<ApiResponse<InventoryItem>>(`/inventory/items/${id}`)
    return response.data.data
  },

  async createInventoryItem(data: Partial<InventoryItem>): Promise<InventoryItem> {
    const response = await api.post<ApiResponse<InventoryItem>>('/inventory/items', data)
    return response.data.data
  },

  async updateInventoryItem(id: number, data: Partial<InventoryItem>): Promise<InventoryItem> {
    const response = await api.put<ApiResponse<InventoryItem>>(`/inventory/items/${id}`, data)
    return response.data.data
  },

  async deleteInventoryItem(id: number): Promise<void> {
    await api.delete(`/inventory/items/${id}`)
  },

  async getLowStockItems(): Promise<InventoryItem[]> {
    const response = await api.get<ApiResponse<InventoryItem[]>>('/inventory/low-stock')
    return response.data.data
  },

  // Purchase Orders
  async getPurchaseOrders(params?: { status?: string }): Promise<PurchaseOrder[]> {
    const response = await api.get<ApiResponse<PurchaseOrder[]>>('/inventory/purchase-orders', { params })
    return response.data.data
  },

  async getPurchaseOrder(id: number): Promise<PurchaseOrder> {
    const response = await api.get<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}`)
    return response.data.data
  },

  async createPurchaseOrder(data: Partial<PurchaseOrder>): Promise<PurchaseOrder> {
    const response = await api.post<ApiResponse<PurchaseOrder>>('/inventory/purchase-orders', data)
    return response.data.data
  },

  async updatePurchaseOrder(id: number, data: Partial<PurchaseOrder>): Promise<PurchaseOrder> {
    const response = await api.put<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}`, data)
    return response.data.data
  },

  async submitPurchaseOrder(id: number): Promise<PurchaseOrder> {
    const response = await api.post<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}/submit`)
    return response.data.data
  },

  async approvePurchaseOrder(id: number): Promise<PurchaseOrder> {
    const response = await api.post<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}/approve`)
    return response.data.data
  },

  async receivePurchaseOrder(id: number, data: any): Promise<PurchaseOrder> {
    const response = await api.post<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}/receive`, data)
    return response.data.data
  },

  async cancelPurchaseOrder(id: number): Promise<PurchaseOrder> {
    const response = await api.post<ApiResponse<PurchaseOrder>>(`/inventory/purchase-orders/${id}/cancel`)
    return response.data.data
  },

  async deletePurchaseOrder(id: number): Promise<void> {
    await api.delete(`/inventory/purchase-orders/${id}`)
  },

  async getPurchaseOrderReceivingHistory(id: number): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>(`/inventory/purchase-orders/${id}/receiving-history`)
    return response.data.data
  },

  // Purchase Order Items
  async addPurchaseOrderItem(purchaseOrderId: number, data: Partial<PurchaseOrderItem>): Promise<PurchaseOrderItem> {
    const response = await api.post<ApiResponse<PurchaseOrderItem>>(
      `/inventory/purchase-orders/${purchaseOrderId}/items`,
      data
    )
    return response.data.data
  },

  async updatePurchaseOrderItem(
    purchaseOrderId: number,
    itemId: number,
    data: Partial<PurchaseOrderItem>
  ): Promise<PurchaseOrderItem> {
    const response = await api.put<ApiResponse<PurchaseOrderItem>>(
      `/inventory/purchase-orders/${purchaseOrderId}/items/${itemId}`,
      data
    )
    return response.data.data
  },

  async deletePurchaseOrderItem(purchaseOrderId: number, itemId: number): Promise<void> {
    await api.delete(`/inventory/purchase-orders/${purchaseOrderId}/items/${itemId}`)
  },

  // Inventory Item extended methods
  async getInventoryItemPriceHistory(itemId: number): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>(`/inventory/items/${itemId}/price-history`)
    return response.data.data
  },

  async getInventoryItemStockLevel(itemId: number): Promise<any> {
    const response = await api.get<ApiResponse<any>>(`/inventory/items/${itemId}/stock-level`)
    return response.data.data
  },

  // Inventory Adjustments
  async getInventoryAdjustments(): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>('/inventory/adjustments')
    return response.data.data
  },

  async getInventoryAdjustment(id: number): Promise<any> {
    const response = await api.get<ApiResponse<any>>(`/inventory/adjustments/${id}`)
    return response.data.data
  },

  async createInventoryAdjustment(data: any): Promise<any> {
    const response = await api.post<ApiResponse<any>>('/inventory/adjustments', data)
    return response.data.data
  },

  async approveInventoryAdjustment(id: number): Promise<any> {
    const response = await api.post<ApiResponse<any>>(`/inventory/adjustments/${id}/approve`)
    return response.data.data
  },

  async getPendingAdjustments(): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>('/inventory/adjustments-pending')
    return response.data.data
  }
}
