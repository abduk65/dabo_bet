<template>
  <div class="space-y-6 pb-12">
    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b px-6 py-4">
      <h1 class="text-2xl font-bold text-gray-900">Business Overview</h1>
      <p class="text-sm text-gray-600 mt-1">{{ today }}</p>
    </div>

    <!-- Critical Metrics - Are we making money? -->
    <div class="px-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Today's Profit/Loss -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4" :class="todayProfit >= 0 ? 'border-green-500' : 'border-red-500'">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Today's Profit/Loss</p>
              <p class="text-3xl font-bold mt-2" :class="todayProfit >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ todayProfit >= 0 ? '+' : '' }}{{ formatCurrency(todayProfit) }}
              </p>
              <p class="text-xs text-gray-500 mt-1">Revenue: {{ formatCurrency(todayRevenue) }} | Cost: {{ formatCurrency(todayCost) }}</p>
            </div>
          </div>
        </div>

        <!-- Cash Position -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
          <div>
            <p class="text-sm font-medium text-gray-600">Cash on Hand</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ formatCurrency(cashPosition) }}</p>
            <p class="text-xs text-gray-500 mt-1">Last updated: {{ lastUpdated }}</p>
          </div>
        </div>

        <!-- Monthly Profit -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4" :class="monthlyProfit >= 0 ? 'border-green-500' : 'border-red-500'">
          <div>
            <p class="text-sm font-medium text-gray-600">This Month</p>
            <p class="text-3xl font-bold mt-2" :class="monthlyProfit >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ formatCurrency(monthlyProfit) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ currentMonth }} profit/loss</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily Reconciliation - Where did the products go? -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
          <h2 class="text-lg font-bold text-gray-900">Today's Reconciliation</h2>
          <p class="text-sm text-gray-600">Track production → dispatch → sales → leftovers</p>
        </div>
        <div class="p-6">
          <div class="space-y-6">
            <div v-for="product in reconciliation" :key="product.id" class="border rounded-lg p-4">
              <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">{{ product.name }}</h3>
                <span v-if="product.variance !== 0" class="px-3 py-1 rounded-full text-xs font-semibold"
                      :class="product.variance < 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'">
                  {{ Math.abs(product.variance) }} missing
                </span>
              </div>

              <div class="grid grid-cols-5 gap-4 text-center">
                <div>
                  <p class="text-2xl font-bold text-gray-900">{{ product.produced }}</p>
                  <p class="text-xs text-gray-600 mt-1">Produced</p>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-500">{{ product.dispatched }}</p>
                  <p class="text-xs text-gray-600 mt-1">Dispatched</p>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-900">{{ product.soldMain }}</p>
                  <p class="text-xs text-gray-600 mt-1">Sold (Main)</p>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-500">{{ product.soldBranches }}</p>
                  <p class="text-xs text-gray-600 mt-1">Sold (Branches)</p>
                </div>
                <div>
                  <p class="text-2xl font-bold" :class="product.leftover > 20 ? 'text-yellow-600' : 'text-gray-900'">
                    {{ product.leftover }}
                  </p>
                  <p class="text-xs text-gray-600 mt-1">Leftover</p>
                </div>
              </div>

              <!-- Formula explanation -->
              <div class="mt-4 p-3 bg-gray-50 rounded text-xs text-gray-600">
                <p class="font-mono">
                  {{ product.produced }} (produced) - {{ product.dispatched}} (dispatched) = {{ product.soldMain + product.leftover }} (main stock)
                </p>
                <p class="font-mono mt-1">
                  {{ product.soldMain }} (sold at main) + {{ product.leftover }} (leftover) + {{ product.soldBranches }} (sold at branches)
                  = {{ product.soldMain + product.leftover + product.soldBranches }} / {{ product.produced }} produced
                </p>
                <p v-if="product.variance !== 0" class="text-red-600 font-semibold mt-2">
                  ⚠️ Variance: {{ Math.abs(product.variance) }} pieces unaccounted for (possible theft/waste)
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Branch Performance - Which branch is making money? -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
          <h2 class="text-lg font-bold text-gray-900">Branch Performance</h2>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="branch in branches" :key="branch.id" class="flex items-center justify-between p-4 border rounded-lg">
              <div>
                <h3 class="font-bold text-gray-900">{{ branch.name }}</h3>
                <p class="text-sm text-gray-600">{{ branch.sales }} sales today</p>
              </div>
              <div class="text-right">
                <p class="text-xl font-bold" :class="branch.profit >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatCurrency(branch.profit) }}
                </p>
                <p class="text-xs text-gray-500">{{ branch.margin }}% margin</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cash Variance Alerts -->
    <div class="px-6" v-if="cashVariances.length > 0">
      <div class="bg-red-50 border border-red-200 rounded-lg">
        <div class="px-6 py-4 border-b border-red-200">
          <h2 class="text-lg font-bold text-red-900">⚠️ Cash Variance Alerts</h2>
          <p class="text-sm text-red-700">Discrepancies exceeding 3% threshold</p>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            <div v-for="variance in cashVariances" :key="variance.id" class="flex items-center justify-between p-3 bg-white rounded border border-red-200">
              <div>
                <p class="font-semibold text-gray-900">{{ variance.branch }}</p>
                <p class="text-sm text-gray-600">{{ variance.date }} - {{ variance.cashier }}</p>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold text-red-600">{{ formatCurrency(variance.amount) }}</p>
                <p class="text-xs text-red-700">{{ variance.percentage }}% variance</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Inventory Alerts - Low stock -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
          <h2 class="text-lg font-bold text-gray-900">Inventory Alerts</h2>
        </div>
        <div class="p-6">
          <div v-if="lowStockItems.length === 0" class="text-center py-8 text-gray-500">
            <p>All inventory levels are healthy</p>
          </div>
          <div v-else class="space-y-2">
            <div v-for="item in lowStockItems" :key="item.id" class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded">
              <div>
                <p class="font-semibold text-gray-900">{{ item.name }}</p>
                <p class="text-sm text-gray-600">Reorder level: {{ item.reorderLevel }} {{ item.unit }}</p>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold text-yellow-700">{{ item.currentStock }} {{ item.unit }}</p>
                <p class="text-xs text-yellow-600">Low stock</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

// Mock data - replace with API calls
const todayRevenue = ref(45000)
const todayCost = ref(32000)
const todayProfit = computed(() => todayRevenue.value - todayCost.value)
const monthlyProfit = ref(387000)
const cashPosition = ref(125000)
const lastUpdated = ref('2 hours ago')

const today = computed(() => {
  return new Date().toLocaleDateString('en-ET', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const currentMonth = computed(() => {
  return new Date().toLocaleDateString('en-ET', { month: 'long', year: 'numeric' })
})

// Reconciliation data
const reconciliation = ref([
  {
    id: 1,
    name: 'White Bread',
    produced: 500,
    dispatched: 350,
    soldMain: 130,
    soldBranches: 315,
    leftover: 20,
    variance: 500 - 350 - 130 - 20 - 315 // Should be 0 if no theft
  },
  {
    id: 2,
    name: 'Brown Bread',
    produced: 400,
    dispatched: 300,
    soldMain: 85,
    soldBranches: 280,
    leftover: 15,
    variance: 400 - 300 - 85 - 15 - 280 // Should be 0
  }
])

// Branch performance
const branches = ref([
  {
    id: 1,
    name: 'Main Branch',
    sales: 130,
    profit: 1300,
    margin: 40
  },
  {
    id: 2,
    name: 'Piassa Branch',
    sales: 195,
    profit: 1950,
    margin: 38
  },
  {
    id: 3,
    name: 'Bole Branch',
    sales: 185,
    profit: 1850,
    margin: 42
  }
])

// Cash variances
const cashVariances = ref([
  {
    id: 1,
    branch: 'Piassa Branch',
    date: 'Today',
    cashier: 'Ahmed M.',
    amount: -450,
    percentage: 3.5
  }
])

// Low stock items
const lowStockItems = ref([
  {
    id: 1,
    name: 'All-Purpose Flour (Mama)',
    currentStock: 85,
    reorderLevel: 100,
    unit: 'kg'
  },
  {
    id: 2,
    name: 'Yeast (Momona)',
    currentStock: 8,
    reorderLevel: 10,
    unit: 'kg'
  }
])

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

onMounted(async () => {
  // Load dashboard data from API
  // await loadDashboardData()
})
</script>
