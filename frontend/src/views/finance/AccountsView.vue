<template>
  <div>
    <PageHeader title="Chart of Accounts" description="View accounting categories and account balances" />

    <!-- Account Type Filter -->
    <div class="card mb-6">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="type in accountTypes"
          :key="type.value"
          @click="selectedType = type.value"
          :class="[
            'px-4 py-2 text-sm font-medium rounded-md',
            selectedType === type.value
              ? 'bg-primary-600 text-white'
              : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
          ]"
        >
          {{ type.label }}
        </button>
      </div>
    </div>

    <!-- Accounts Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredAccounts"
        :loading="loading"
        loading-text="Loading accounts..."
        empty-text="No accounts found."
      >
        <template #cell-account_type="{ value }">
          <span :class="getTypeBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #cell-is_active="{ value }">
          <span :class="value ? 'badge-success' : 'badge-gray'">
            {{ value ? 'Active' : 'Inactive' }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewBalance(row)" class="text-primary-600 hover:text-primary-900">
            View Balance
          </button>
        </template>
      </SimpleTable>
    </div>

    <!-- Trial Balance Button -->
    <div class="mt-6">
      <button @click="viewTrialBalance" class="btn-primary">
        View Trial Balance
      </button>
    </div>

    <!-- Balance Modal -->
    <Modal v-model:show="showBalanceModal" :title="`Account Balance - ${selectedAccount?.account_name}`" size="md">
      <div v-if="loadingBalance" class="py-12">
        <LoadingSpinner text="Loading balance..." />
      </div>
      <div v-else-if="accountBalance" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div class="card bg-green-50">
            <p class="text-sm text-gray-600">Total Debits</p>
            <p class="text-2xl font-bold text-green-600">{{ formatCurrency(accountBalance.debit_total) }}</p>
          </div>
          <div class="card bg-red-50">
            <p class="text-sm text-gray-600">Total Credits</p>
            <p class="text-2xl font-bold text-red-600">{{ formatCurrency(accountBalance.credit_total) }}</p>
          </div>
        </div>
        <div class="card bg-blue-50">
          <p class="text-sm text-gray-600">Current Balance</p>
          <p class="text-3xl font-bold text-blue-600">{{ formatCurrency(accountBalance.balance) }}</p>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { financeService, type Account } from '@/services/finance.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const loading = ref(true)
const accounts = ref<Account[]>([])
const selectedType = ref<string | undefined>(undefined)

const showBalanceModal = ref(false)
const loadingBalance = ref(false)
const selectedAccount = ref<Account | null>(null)
const accountBalance = ref<any>(null)

const accountTypes = [
  { value: undefined, label: 'All' },
  { value: 'asset', label: 'Assets' },
  { value: 'liability', label: 'Liabilities' },
  { value: 'equity', label: 'Equity' },
  { value: 'revenue', label: 'Revenue' },
  { value: 'expense', label: 'Expenses' }
]

const columns = [
  { key: 'account_code', label: 'Code', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'account_name', label: 'Account Name', cellClass: 'text-sm text-gray-900' },
  { key: 'account_type', label: 'Type', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'description', label: 'Description', cellClass: 'text-sm text-gray-500' },
  { key: 'is_active', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

const filteredAccounts = computed(() => {
  if (!selectedType.value) return accounts.value
  return accounts.value.filter(a => a.account_type === selectedType.value)
})

onMounted(() => loadAccounts())

async function loadAccounts() {
  try {
    loading.value = true
    accounts.value = await financeService.getChartOfAccounts()
  } catch (err) {
    console.error('Failed to load accounts:', err)
  } finally {
    loading.value = false
  }
}

function getTypeBadgeClass(type: string): string {
  const classes: Record<string, string> = {
    asset: 'badge-blue',
    liability: 'badge-red',
    equity: 'badge-purple',
    revenue: 'badge-green',
    expense: 'badge-yellow'
  }
  return classes[type] || 'badge-gray'
}

async function viewBalance(account: Account) {
  selectedAccount.value = account
  showBalanceModal.value = true
  loadingBalance.value = true

  try {
    accountBalance.value = await financeService.getAccountBalance(account.id)
  } catch (err) {
    console.error('Failed to load balance:', err)
  } finally {
    loadingBalance.value = false
  }
}

function viewTrialBalance() {
  // Navigate to trial balance view or open modal
  console.log('View trial balance')
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 2
  }).format(amount)
}
</script>
