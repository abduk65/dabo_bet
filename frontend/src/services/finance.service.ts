import api from './api'
import type { ApiResponse } from '@/types'

export interface Account {
  id: number
  account_code: string
  account_name: string
  account_type: 'asset' | 'liability' | 'equity' | 'revenue' | 'expense'
  parent_account_id?: number
  is_active: boolean
  description?: string
  created_at?: string
  parent?: Account
  children?: Account[]
}

export interface JournalEntry {
  id: number
  entry_number: string
  entry_date: string
  entry_type: 'general' | 'purchase' | 'sales' | 'payment' | 'adjustment'
  description: string
  status: 'draft' | 'posted' | 'reversed'
  reference_type?: string
  reference_id?: number
  created_by_user_id: number
  posted_at?: string
  lines?: JournalEntryLine[]
}

export interface JournalEntryLine {
  id: number
  journal_entry_id: number
  account_id: number
  debit_amount: number
  credit_amount: number
  description?: string
  account?: Account
}

export interface TrialBalanceItem {
  account_code: string
  account_name: string
  account_type: string
  debit_total: number
  credit_total: number
}

export const financeService = {
  // Accounts
  async getAccounts(params?: { type?: string }): Promise<Account[]> {
    const response = await api.get<ApiResponse<Account[]>>('/accounts', { params })
    return response.data.data
  },

  async getAccount(id: number): Promise<Account> {
    const response = await api.get<ApiResponse<Account>>(`/accounts/${id}`)
    return response.data.data
  },

  async getAccountBalance(id: number): Promise<{ balance: number; debit_total: number; credit_total: number }> {
    const response = await api.get<ApiResponse<any>>(`/accounts/${id}/balance`)
    return response.data.data
  },

  async getAccountsByType(type: string): Promise<Account[]> {
    const response = await api.get<ApiResponse<Account[]>>(`/accounts/type/${type}`)
    return response.data.data
  },

  async getChartOfAccounts(): Promise<Account[]> {
    const response = await api.get<ApiResponse<Account[]>>('/chart-of-accounts')
    return response.data.data
  },

  async getTrialBalance(): Promise<TrialBalanceItem[]> {
    const response = await api.get<ApiResponse<TrialBalanceItem[]>>('/trial-balance')
    return response.data.data
  },

  // Journal Entries
  async getJournalEntries(params?: { type?: string; status?: string }): Promise<JournalEntry[]> {
    const response = await api.get<ApiResponse<JournalEntry[]>>('/journal-entries', { params })
    return response.data.data
  },

  async getJournalEntry(id: number): Promise<JournalEntry> {
    const response = await api.get<ApiResponse<JournalEntry>>(`/journal-entries/${id}`)
    return response.data.data
  },

  async createJournalEntry(data: Partial<JournalEntry> & { lines: Partial<JournalEntryLine>[] }): Promise<JournalEntry> {
    const response = await api.post<ApiResponse<JournalEntry>>('/journal-entries', data)
    return response.data.data
  },

  async updateJournalEntry(id: number, data: Partial<JournalEntry>): Promise<JournalEntry> {
    const response = await api.put<ApiResponse<JournalEntry>>(`/journal-entries/${id}`, data)
    return response.data.data
  },

  async deleteJournalEntry(id: number): Promise<void> {
    await api.delete(`/journal-entries/${id}`)
  },

  async postJournalEntry(id: number): Promise<JournalEntry> {
    const response = await api.post<ApiResponse<JournalEntry>>(`/journal-entries/${id}/post`)
    return response.data.data
  },

  async reverseJournalEntry(id: number, data: { reversal_date: string; description: string }): Promise<JournalEntry> {
    const response = await api.post<ApiResponse<JournalEntry>>(`/journal-entries/${id}/reverse`, data)
    return response.data.data
  },

  async getDraftJournalEntries(): Promise<JournalEntry[]> {
    const response = await api.get<ApiResponse<JournalEntry[]>>('/journal-entries-drafts')
    return response.data.data
  },

  async getJournalEntriesByType(type: string): Promise<JournalEntry[]> {
    const response = await api.get<ApiResponse<JournalEntry[]>>(`/journal-entries/type/${type}`)
    return response.data.data
  },

  // Reports
  async getIncomeStatement(params: { start_date: string; end_date: string }): Promise<any> {
    // This would be implemented when the backend endpoint exists
    const response = await api.get<ApiResponse<any>>('/reports/income-statement', { params })
    return response.data.data
  },

  async getBalanceSheet(params: { as_of_date: string }): Promise<any> {
    // This would be implemented when the backend endpoint exists
    const response = await api.get<ApiResponse<any>>('/reports/balance-sheet', { params })
    return response.data.data
  },

  async getCashFlowStatement(params: { start_date: string; end_date: string }): Promise<any> {
    // This would be implemented when the backend endpoint exists
    const response = await api.get<ApiResponse<any>>('/reports/cash-flow', { params })
    return response.data.data
  }
}
