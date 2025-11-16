<template>
  <div>
    <PageHeader title="Financial Reports" description="View comprehensive financial statements and reports" />

    <!-- Report Selection -->
    <div class="card mb-6">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Report Type *</label>
          <select v-model="selectedReport" class="input mt-1" @change="onReportChange">
            <option value="">Select Report</option>
            <option value="income_statement">Income Statement</option>
            <option value="balance_sheet">Balance Sheet</option>
            <option value="cash_flow">Cash Flow Statement</option>
            <option value="trial_balance">Trial Balance</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">From Date *</label>
          <input v-model="filters.fromDate" type="date" class="input mt-1" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">To Date *</label>
          <input v-model="filters.toDate" type="date" class="input mt-1" />
        </div>
        <div class="flex items-end">
          <button @click="generateReport" :disabled="!selectedReport || loading" class="btn-primary w-full">
            {{ loading ? 'Generating...' : 'Generate Report' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Report Display -->
    <div v-if="reportData" class="card">
      <!-- Report Header -->
      <div class="border-b pb-4 mb-4">
        <h2 class="text-xl font-bold text-gray-900">{{ reportTitle }}</h2>
        <p class="text-sm text-gray-600 mt-1">
          Period: {{ formatDate(filters.fromDate) }} to {{ formatDate(filters.toDate) }}
        </p>
        <div class="mt-3 flex gap-2">
          <button @click="printReport" class="btn-sm">Print</button>
          <button @click="exportReport" class="btn-sm">Export to Excel</button>
        </div>
      </div>

      <!-- Income Statement -->
      <div v-if="selectedReport === 'income_statement'" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Revenue Section -->
            <tr class="bg-blue-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">REVENUE</td>
            </tr>
            <tr v-for="item in reportData.revenue" :key="item.account_code">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.amount) }}</td>
            </tr>
            <tr class="font-semibold bg-blue-100">
              <td class="px-6 py-2 text-sm text-gray-900">Total Revenue</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_revenue) }}</td>
            </tr>

            <!-- Expenses Section -->
            <tr class="bg-red-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">EXPENSES</td>
            </tr>
            <tr v-for="item in reportData.expenses" :key="item.account_code">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.amount) }}</td>
            </tr>
            <tr class="font-semibold bg-red-100">
              <td class="px-6 py-2 text-sm text-gray-900">Total Expenses</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_expenses) }}</td>
            </tr>

            <!-- Net Income -->
            <tr class="bg-gray-100 border-t-2 border-gray-300">
              <td class="px-6 py-3 text-base font-bold text-gray-900">NET INCOME</td>
              <td class="px-6 py-3 text-base font-bold text-right" :class="reportData.net_income >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(reportData.net_income) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Balance Sheet -->
      <div v-else-if="selectedReport === 'balance_sheet'" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Assets Section -->
            <tr class="bg-green-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">ASSETS</td>
            </tr>
            <tr v-for="item in reportData.assets" :key="item.account_code">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.balance) }}</td>
            </tr>
            <tr class="font-semibold bg-green-100">
              <td class="px-6 py-2 text-sm text-gray-900">Total Assets</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_assets) }}</td>
            </tr>

            <!-- Liabilities Section -->
            <tr class="bg-red-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900 mt-4">LIABILITIES</td>
            </tr>
            <tr v-for="item in reportData.liabilities" :key="item.account_code">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.balance) }}</td>
            </tr>
            <tr class="font-semibold bg-red-100">
              <td class="px-6 py-2 text-sm text-gray-900">Total Liabilities</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_liabilities) }}</td>
            </tr>

            <!-- Equity Section -->
            <tr class="bg-purple-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900 mt-4">EQUITY</td>
            </tr>
            <tr v-for="item in reportData.equity" :key="item.account_code">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.balance) }}</td>
            </tr>
            <tr class="font-semibold bg-purple-100">
              <td class="px-6 py-2 text-sm text-gray-900">Total Equity</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_equity) }}</td>
            </tr>

            <!-- Total Liabilities + Equity -->
            <tr class="bg-gray-100 border-t-2 border-gray-300">
              <td class="px-6 py-3 text-base font-bold text-gray-900">TOTAL LIABILITIES & EQUITY</td>
              <td class="px-6 py-3 text-base font-bold text-gray-900 text-right">
                {{ formatCurrency(reportData.total_liabilities + reportData.total_equity) }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Balance Check -->
        <div class="mt-4 p-4 rounded-md" :class="isBalanced ? 'bg-green-50' : 'bg-red-50'">
          <p class="text-sm font-medium" :class="isBalanced ? 'text-green-800' : 'text-red-800'">
            {{ isBalanced ? '✓ Balance Sheet is balanced' : '⚠ Balance Sheet is not balanced - please review accounts' }}
          </p>
        </div>
      </div>

      <!-- Cash Flow Statement -->
      <div v-else-if="selectedReport === 'cash_flow'" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Operating Activities -->
            <tr class="bg-blue-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">OPERATING ACTIVITIES</td>
            </tr>
            <tr v-for="item in reportData.operating_activities" :key="item.description">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.description }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.amount) }}</td>
            </tr>
            <tr class="font-semibold bg-blue-100">
              <td class="px-6 py-2 text-sm text-gray-900">Net Cash from Operating Activities</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.net_operating) }}</td>
            </tr>

            <!-- Investing Activities -->
            <tr class="bg-purple-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">INVESTING ACTIVITIES</td>
            </tr>
            <tr v-for="item in reportData.investing_activities" :key="item.description">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.description }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.amount) }}</td>
            </tr>
            <tr class="font-semibold bg-purple-100">
              <td class="px-6 py-2 text-sm text-gray-900">Net Cash from Investing Activities</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.net_investing) }}</td>
            </tr>

            <!-- Financing Activities -->
            <tr class="bg-green-50">
              <td colspan="2" class="px-6 py-3 text-sm font-bold text-gray-900">FINANCING ACTIVITIES</td>
            </tr>
            <tr v-for="item in reportData.financing_activities" :key="item.description">
              <td class="px-6 py-2 text-sm text-gray-900 pl-12">{{ item.description }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(item.amount) }}</td>
            </tr>
            <tr class="font-semibold bg-green-100">
              <td class="px-6 py-2 text-sm text-gray-900">Net Cash from Financing Activities</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.net_financing) }}</td>
            </tr>

            <!-- Net Change in Cash -->
            <tr class="bg-gray-100 border-t-2 border-gray-300">
              <td class="px-6 py-3 text-base font-bold text-gray-900">NET CHANGE IN CASH</td>
              <td class="px-6 py-3 text-base font-bold text-right" :class="reportData.net_change_in_cash >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(reportData.net_change_in_cash) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Trial Balance -->
      <div v-else-if="selectedReport === 'trial_balance'" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Credit</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in reportData.accounts" :key="item.account_code">
              <td class="px-6 py-2 text-sm font-medium text-gray-900">{{ item.account_code }}</td>
              <td class="px-6 py-2 text-sm text-gray-900">{{ item.account_name }}</td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">
                {{ item.debit_balance > 0 ? formatCurrency(item.debit_balance) : '-' }}
              </td>
              <td class="px-6 py-2 text-sm text-gray-900 text-right">
                {{ item.credit_balance > 0 ? formatCurrency(item.credit_balance) : '-' }}
              </td>
            </tr>
            <!-- Totals -->
            <tr class="bg-gray-100 border-t-2 border-gray-300 font-bold">
              <td colspan="2" class="px-6 py-3 text-sm text-gray-900">TOTALS</td>
              <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_debits) }}</td>
              <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ formatCurrency(reportData.total_credits) }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Balance Check -->
        <div class="mt-4 p-4 rounded-md" :class="isTrialBalanced ? 'bg-green-50' : 'bg-red-50'">
          <p class="text-sm font-medium" :class="isTrialBalanced ? 'text-green-800' : 'text-red-800'">
            {{ isTrialBalanced ? '✓ Trial balance is balanced' : '⚠ Trial balance is not balanced - please review entries' }}
          </p>
          <p v-if="!isTrialBalanced" class="text-xs mt-1" :class="isTrialBalanced ? 'text-green-700' : 'text-red-700'">
            Difference: {{ formatCurrency(Math.abs(reportData.total_debits - reportData.total_credits)) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="card">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No Report Generated</h3>
        <p class="mt-1 text-sm text-gray-500">Select a report type and date range, then click "Generate Report"</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { financeService } from '@/services/finance.service'
import PageHeader from '@/components/common/PageHeader.vue'

const loading = ref(false)
const selectedReport = ref('')
const reportData = ref<any>(null)

const filters = ref({
  fromDate: new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0], // Jan 1st of current year
  toDate: new Date().toISOString().split('T')[0] // Today
})

const reportTitle = computed(() => {
  const titles: Record<string, string> = {
    income_statement: 'Income Statement',
    balance_sheet: 'Balance Sheet',
    cash_flow: 'Cash Flow Statement',
    trial_balance: 'Trial Balance'
  }
  return titles[selectedReport.value] || 'Financial Report'
})

const isBalanced = computed(() => {
  if (!reportData.value || selectedReport.value !== 'balance_sheet') return true
  const assets = reportData.value.total_assets || 0
  const liabilitiesAndEquity = (reportData.value.total_liabilities || 0) + (reportData.value.total_equity || 0)
  return Math.abs(assets - liabilitiesAndEquity) < 0.01 // Allow for rounding errors
})

const isTrialBalanced = computed(() => {
  if (!reportData.value || selectedReport.value !== 'trial_balance') return true
  const debits = reportData.value.total_debits || 0
  const credits = reportData.value.total_credits || 0
  return Math.abs(debits - credits) < 0.01 // Allow for rounding errors
})

function onReportChange() {
  reportData.value = null
}

async function generateReport() {
  if (!selectedReport.value) return

  try {
    loading.value = true

    const params = {
      from_date: filters.value.fromDate,
      to_date: filters.value.toDate
    }

    switch (selectedReport.value) {
      case 'income_statement':
        reportData.value = await financeService.getIncomeStatement(params)
        break
      case 'balance_sheet':
        reportData.value = await financeService.getBalanceSheet(params)
        break
      case 'cash_flow':
        reportData.value = await financeService.getCashFlowStatement(params)
        break
      case 'trial_balance':
        reportData.value = await financeService.getTrialBalance(params)
        break
    }
  } catch (err) {
    console.error('Failed to generate report:', err)
    alert('Failed to generate report. Please try again.')
  } finally {
    loading.value = false
  }
}

function printReport() {
  window.print()
}

function exportReport() {
  // In a real implementation, this would generate and download an Excel file
  alert('Export to Excel feature will be implemented with backend support')
}

function formatDate(date: string | null | undefined): string {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('en-ET', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 2
  }).format(amount)
}
</script>
