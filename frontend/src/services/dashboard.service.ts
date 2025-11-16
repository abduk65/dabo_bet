import api from '@/api/client'
import type { AxiosResponse } from 'axios'

interface OwnerMetrics {
  todayRevenue: number
  revenueTrend: number
  revenueChange: number
  todayOrders: number
  ordersTrend: number
  profitMargin: number
  profitMarginTrend: number
  cashPosition: number
  cashTrend: number
}

interface Alert {
  id: number
  type: 'stock' | 'finance' | 'security' | 'system' | 'quality' | 'sales'
  severity: 'critical' | 'high' | 'medium' | 'low'
  title: string
  message: string
  created_at: string
  location?: string
  actions?: Array<{
    label: string
    action: string
    primary?: boolean
  }>
}

interface RevenueDataPoint {
  date: string
  revenue: number
  target?: number
  orders?: number
}

interface Branch {
  id: number
  name: string
  location: string
  image?: string
  revenue: number
  revenue_trend: number
  orders: number
  avg_order_value: number
  profit_margin: number
}

interface Product {
  id: number
  name: string
  image?: string
  units_sold: number
  margin: number
  profit: number
  trend: number
  stock_days_remaining?: number
  days_since_last_sale?: number
}

interface ProductPerformance {
  top: Product[]
  bottom: Product[]
}

interface PendingApproval {
  id: number
  type: string
  title: string
  description: string
  amount: number
  requested_by: string
  requested_at: string
}

class DashboardService {
  /**
   * Get owner dashboard metrics
   */
  async getOwnerMetrics(): Promise<OwnerMetrics> {
    const response: AxiosResponse<OwnerMetrics> = await api.get('/dashboard/owner/metrics')
    return response.data
  }

  /**
   * Get alerts
   */
  async getAlerts(): Promise<Alert[]> {
    const response: AxiosResponse<Alert[]> = await api.get('/dashboard/alerts')
    return response.data
  }

  /**
   * Dismiss an alert
   */
  async dismissAlert(alertId: number): Promise<void> {
    await api.post(`/dashboard/alerts/${alertId}/dismiss`)
  }

  /**
   * Get revenue data for chart
   */
  async getRevenueData(period: string = '7d'): Promise<RevenueDataPoint[]> {
    const response: AxiosResponse<RevenueDataPoint[]> = await api.get('/dashboard/revenue', {
      params: { period }
    })
    return response.data
  }

  /**
   * Get branch comparison data
   */
  async getBranchComparison(period: string = 'today'): Promise<Branch[]> {
    const response: AxiosResponse<Branch[]> = await api.get('/dashboard/branches', {
      params: { period }
    })
    return response.data
  }

  /**
   * Get product performance data
   */
  async getProductPerformance(): Promise<ProductPerformance> {
    const response: AxiosResponse<ProductPerformance> = await api.get('/dashboard/products/performance')
    return response.data
  }

  /**
   * Get pending approvals
   */
  async getPendingApprovals(): Promise<PendingApproval[]> {
    const response: AxiosResponse<PendingApproval[]> = await api.get('/dashboard/approvals/pending')
    return response.data
  }

  /**
   * Get manager dashboard metrics
   */
  async getManagerMetrics(): Promise<any> {
    const response = await api.get('/dashboard/manager/metrics')
    return response.data
  }

  /**
   * Get manager task list
   */
  async getManagerTasks(): Promise<any[]> {
    const response = await api.get('/dashboard/manager/tasks')
    return response.data
  }

  /**
   * Get approval queue for manager
   */
  async getApprovalQueue(filters?: {
    type?: string
    status?: string
    priority?: string
  }): Promise<any[]> {
    const response = await api.get('/dashboard/manager/approvals', {
      params: filters
    })
    return response.data
  }

  /**
   * Approve multiple items
   */
  async bulkApprove(approvalIds: number[], notes?: string): Promise<void> {
    await api.post('/dashboard/manager/approvals/bulk-approve', {
      approval_ids: approvalIds,
      notes
    })
  }

  /**
   * Reject multiple items
   */
  async bulkReject(approvalIds: number[], reason: string): Promise<void> {
    await api.post('/dashboard/manager/approvals/bulk-reject', {
      approval_ids: approvalIds,
      reason
    })
  }

  /**
   * Get team performance metrics
   */
  async getTeamPerformance(): Promise<any[]> {
    const response = await api.get('/dashboard/manager/team-performance')
    return response.data
  }

  /**
   * Get cashier session summary
   */
  async getCashierSessionSummary(): Promise<any> {
    const response = await api.get('/dashboard/cashier/session')
    return response.data
  }

  /**
   * Get stock clerk tasks
   */
  async getStockClerkTasks(): Promise<any[]> {
    const response = await api.get('/dashboard/stock-clerk/tasks')
    return response.data
  }
}

export const dashboardService = new DashboardService()
