import api from './api'
import type { ApiResponse } from '@/types'

export interface Unit {
  id: number
  unit_name: string
  abbreviation: string
  unit_type: 'weight' | 'volume' | 'count' | 'length' | 'other'
  description?: string
}

export interface Branch {
  id: number
  name: string
  type: 'main' | 'sub'
  address?: string
  phone?: string
  is_active: boolean
}

export const referenceService = {
  async getUnits(): Promise<Unit[]> {
    const response = await api.get<ApiResponse<Unit[]>>('/units')
    return response.data.data
  },

  async getBranches(): Promise<Branch[]> {
    const response = await api.get<ApiResponse<Branch[]>>('/branches')
    return response.data.data
  }
}
