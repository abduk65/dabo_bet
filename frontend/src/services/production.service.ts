import api from './api'
import type { ApiResponse } from '@/types'

export interface Product {
  id: number
  product_code: string
  product_name: string
  product_type: 'bread' | 'pastry' | 'cake' | 'other'
  unit_id: number
  shelf_life_days: number
  description?: string
  is_active: boolean
  unit?: {
    id: number
    unit_name: string
    abbreviation: string
  }
  recipes?: any[]
  created_at?: string
  updated_at?: string
}

export interface ProductPrice {
  id: number
  product_id: number
  price: number
  effective_date: string
  is_current: boolean
}

export interface Recipe {
  id: number
  product_id: number
  recipe_version: string
  batch_size: number
  unit_id: number
  prep_time_minutes?: number
  bake_time_minutes?: number
  total_time_minutes?: number
  instructions?: string
  is_active: boolean
  created_by_user_id: number
  product?: Product
  components?: RecipeComponent[]
}

export interface RecipeComponent {
  id: number
  recipe_id: number
  inventory_item_id: number
  quantity: number
  unit_id: number
  can_substitute: boolean
  notes?: string
}

export interface ProductionOrder {
  id: number
  production_number: string
  recipe_id: number
  planned_quantity: number
  actual_quantity?: number
  production_date: string
  status: 'planned' | 'in_progress' | 'completed' | 'cancelled'
  created_by_user_id: number
  recipe?: Recipe
  consumptions?: any[]
  outputs?: any[]
}

export const productionService = {
  // Products
  async getProducts(params?: { type?: string }): Promise<Product[]> {
    const response = await api.get<ApiResponse<Product[]>>('/production/products', { params })
    return response.data.data
  },

  async getProduct(id: number): Promise<Product> {
    const response = await api.get<ApiResponse<Product>>(`/production/products/${id}`)
    return response.data.data
  },

  async createProduct(data: Partial<Product>): Promise<Product> {
    const response = await api.post<ApiResponse<Product>>('/production/products', data)
    return response.data.data
  },

  async updateProduct(id: number, data: Partial<Product>): Promise<Product> {
    const response = await api.put<ApiResponse<Product>>(`/production/products/${id}`, data)
    return response.data.data
  },

  async deleteProduct(id: number): Promise<void> {
    await api.delete(`/production/products/${id}`)
  },

  async getProductsByType(type: string): Promise<Product[]> {
    const response = await api.get<ApiResponse<Product[]>>(`/production/products/type/${type}`)
    return response.data.data
  },

  async setProductPrice(productId: number, data: { price: number; effective_date: string }): Promise<ProductPrice> {
    const response = await api.post<ApiResponse<ProductPrice>>(`/production/products/${productId}/set-price`, data)
    return response.data.data
  },

  async getProductPriceHistory(productId: number): Promise<ProductPrice[]> {
    const response = await api.get<ApiResponse<ProductPrice[]>>(`/production/products/${productId}/price-history`)
    return response.data.data
  },

  // Recipes
  async getRecipes(): Promise<Recipe[]> {
    const response = await api.get<ApiResponse<Recipe[]>>('/production/recipes')
    return response.data.data
  },

  async getRecipe(id: number): Promise<Recipe> {
    const response = await api.get<ApiResponse<Recipe>>(`/production/recipes/${id}`)
    return response.data.data
  },

  async createRecipe(data: Partial<Recipe>): Promise<Recipe> {
    const response = await api.post<ApiResponse<Recipe>>('/production/recipes', data)
    return response.data.data
  },

  async updateRecipe(id: number, data: Partial<Recipe>): Promise<Recipe> {
    const response = await api.put<ApiResponse<Recipe>>(`/production/recipes/${id}`, data)
    return response.data.data
  },

  async deleteRecipe(id: number): Promise<void> {
    await api.delete(`/production/recipes/${id}`)
  },

  async getRecipeComponents(recipeId: number): Promise<RecipeComponent[]> {
    const response = await api.get<ApiResponse<RecipeComponent[]>>(`/production/recipes/${recipeId}/components`)
    return response.data.data
  },

  async calculateRecipeCost(recipeId: number): Promise<{ total_cost: number; cost_per_unit: number }> {
    const response = await api.post<ApiResponse<any>>(`/production/recipes/${recipeId}/calculate-cost`)
    return response.data.data
  },

  async activateRecipe(recipeId: number): Promise<Recipe> {
    const response = await api.post<ApiResponse<Recipe>>(`/production/recipes/${recipeId}/activate`)
    return response.data.data
  },

  // Production Orders
  async getProductionOrders(): Promise<ProductionOrder[]> {
    const response = await api.get<ApiResponse<ProductionOrder[]>>('/production/orders')
    return response.data.data
  },

  async getProductionOrder(id: number): Promise<ProductionOrder> {
    const response = await api.get<ApiResponse<ProductionOrder>>(`/production/orders/${id}`)
    return response.data.data
  },

  async createProductionOrder(data: Partial<ProductionOrder>): Promise<ProductionOrder> {
    const response = await api.post<ApiResponse<ProductionOrder>>('/production/orders', data)
    return response.data.data
  },

  async updateProductionOrder(id: number, data: Partial<ProductionOrder>): Promise<ProductionOrder> {
    const response = await api.put<ApiResponse<ProductionOrder>>(`/production/orders/${id}`, data)
    return response.data.data
  },

  async deleteProductionOrder(id: number): Promise<void> {
    await api.delete(`/production/orders/${id}`)
  },

  async startProductionOrder(id: number): Promise<ProductionOrder> {
    const response = await api.post<ApiResponse<ProductionOrder>>(`/production/orders/${id}/start`)
    return response.data.data
  },

  async recordConsumption(orderId: number, data: any): Promise<any> {
    const response = await api.post<ApiResponse<any>>(`/production/orders/${orderId}/record-consumption`, data)
    return response.data.data
  },

  async recordOutput(orderId: number, data: any): Promise<any> {
    const response = await api.post<ApiResponse<any>>(`/production/orders/${orderId}/record-output`, data)
    return response.data.data
  },

  async completeProductionOrder(id: number): Promise<ProductionOrder> {
    const response = await api.post<ApiResponse<ProductionOrder>>(`/production/orders/${id}/complete`)
    return response.data.data
  },

  async getProductionOrderConsumption(orderId: number): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>(`/production/orders/${orderId}/consumption`)
    return response.data.data
  },

  async getProductionOrderOutput(orderId: number): Promise<any[]> {
    const response = await api.get<ApiResponse<any[]>>(`/production/orders/${orderId}/output`)
    return response.data.data
  }
}
