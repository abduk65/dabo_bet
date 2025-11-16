<template>
  <div class="owner-dashboard min-h-screen bg-gray-50">
    <!-- Page header -->
    <div class="bg-white shadow">
      <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Owner Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">
              Financial snapshot as of {{ formatDateTime(new Date()) }}
            </p>
          </div>

          <!-- Refresh indicator -->
          <div class="flex items-center gap-3">
            <button
              @click="refreshDashboard"
              type="button"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              <ArrowPathIcon :class="['h-5 w-5 mr-2', refreshing && 'animate-spin']" />
              Refresh
            </button>

            <!-- Auto-refresh toggle -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Auto-refresh:</span>
              <button
                @click="toggleAutoRefresh"
                type="button"
                :class="[
                  'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
                  autoRefresh ? 'bg-primary-600' : 'bg-gray-200'
                ]"
              >
                <span
                  :class="[
                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                    autoRefresh ? 'translate-x-5' : 'translate-x-0'
                  ]"
                />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
      <!-- Key Metrics Row -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <MetricCard
          label="Today's Revenue"
          :value="metrics.todayRevenue"
          :trend-percentage="metrics.revenueTrend"
          :change="metrics.revenueChange"
          variant="primary"
          :icon="BanknotesIcon"
          clickable
          @click="navigateTo('/reports/revenue')"
        />

        <MetricCard
          label="Today's Orders"
          :value="metrics.todayOrders"
          unit="orders"
          :trend-percentage="metrics.ordersTrend"
          variant="success"
          :icon="ShoppingCartIcon"
          :format-currency="() => String(metrics.todayOrders)"
          clickable
          @click="navigateTo('/sales/orders')"
        />

        <MetricCard
          label="Profit Margin"
          :value="metrics.profitMargin"
          unit="%"
          :trend-percentage="metrics.profitMarginTrend"
          :variant="metrics.profitMargin >= 25 ? 'success' : metrics.profitMargin >= 15 ? 'warning' : 'danger'"
          :icon="ChartBarIcon"
          :format-currency="() => `${metrics.profitMargin.toFixed(1)}%`"
        />

        <MetricCard
          label="Cash Position"
          :value="metrics.cashPosition"
          :trend-percentage="metrics.cashTrend"
          variant="info"
          :icon="CurrencyDollarIcon"
        />
      </div>

      <!-- Alerts Summary -->
      <AlertSummary
        v-if="alerts.length > 0"
        :alerts="alerts"
        @dismiss="handleDismissAlert"
        @view-all="navigateTo('/alerts')"
      />

      <!-- Revenue Trend Chart -->
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">7-Day Revenue Trend</h2>
          <div class="flex items-center gap-2">
            <button
              v-for="period in ['7d', '30d', '90d']"
              :key="period"
              @click="revenuePeriod = period"
              :class="[
                'px-3 py-1 text-sm font-medium rounded-md',
                revenuePeriod === period
                  ? 'bg-primary-100 text-primary-700'
                  : 'text-gray-600 hover:bg-gray-100'
              ]"
            >
              {{ period }}
            </button>
          </div>
        </div>
        <RevenueChart
          :data="revenueChartData"
          :loading="loadingRevenue"
          :height="300"
        />
      </div>

      <!-- Branch Comparison -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Branch Performance</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <BranchComparisonCard
            v-for="branch in branches"
            :key="branch.id"
            :branch="branch"
            :comparison-period="branchComparisonPeriod"
            @click="navigateTo(`/branches/${branch.id}`)"
          />
        </div>
      </div>

      <!-- Product Performance -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Top performers -->
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Top Products</h2>
            <span class="text-sm text-gray-500">By profitability</span>
          </div>
          <ProductPerformanceList
            :products="topProducts"
            :loading="loadingProducts"
            type="top"
          />
        </div>

        <!-- Bottom performers -->
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Products Needing Attention</h2>
            <span class="text-sm text-gray-500">Low margin / slow moving</span>
          </div>
          <ProductPerformanceList
            :products="bottomProducts"
            :loading="loadingProducts"
            type="bottom"
          />
        </div>
      </div>

      <!-- Pending Approvals -->
      <div v-if="pendingApprovals.length > 0" class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <h2 class="text-lg font-semibold text-gray-900">Pending Approvals</h2>
            <StatusBadge :label="String(pendingApprovals.length)" variant="yellow" />
          </div>
          <button
            @click="navigateTo('/approvals')"
            class="text-sm font-medium text-primary-600 hover:text-primary-700"
          >
            View all
          </button>
        </div>

        <div class="space-y-3">
          <div
            v-for="approval in pendingApprovals.slice(0, 5)"
            :key="approval.id"
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer"
            @click="handleApprovalClick(approval)"
          >
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">{{ approval.title }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ approval.description }}</p>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-sm font-medium text-gray-900">
                {{ formatCurrency(approval.amount) }}
              </span>
              <ChevronRightIcon class="h-5 w-5 text-gray-400" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  ArrowPathIcon,
  BanknotesIcon,
  ShoppingCartIcon,
  ChartBarIcon,
  CurrencyDollarIcon,
  ChevronRightIcon
} from '@heroicons/vue/24/outline'
import MetricCard from '@/components/common/MetricCard.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'
import AlertSummary from '@/components/dashboard/AlertSummary.vue'
import RevenueChart from '@/components/dashboard/RevenueChart.vue'
import BranchComparisonCard from '@/components/dashboard/BranchComparisonCard.vue'
import ProductPerformanceList from '@/components/dashboard/ProductPerformanceList.vue'
import { formatCurrency, formatDateTime } from '@/utils/formatters'
import { useNotification } from '@/composables/useNotification'
import { dashboardService } from '@/services/dashboard.service'

const router = useRouter()
const { notify } = useNotification()

// State
const refreshing = ref(false)
const autoRefresh = ref(true)
const autoRefreshInterval = ref<number>()
const revenuePeriod = ref('7d')
const branchComparisonPeriod = ref('today')

const metrics = ref({
  todayRevenue: 0,
  revenueTrend: 0,
  revenueChange: 0,
  todayOrders: 0,
  ordersTrend: 0,
  profitMargin: 0,
  profitMarginTrend: 0,
  cashPosition: 0,
  cashTrend: 0
})

const alerts = ref<any[]>([])
const revenueChartData = ref<any[]>([])
const branches = ref<any[]>([])
const topProducts = ref<any[]>([])
const bottomProducts = ref<any[]>([])
const pendingApprovals = ref<any[]>([])

const loadingRevenue = ref(false)
const loadingProducts = ref(false)

// Methods
async function loadDashboardData() {
  refreshing.value = true

  try {
    // Load all dashboard data in parallel
    const [
      metricsData,
      alertsData,
      revenueData,
      branchesData,
      productsData,
      approvalsData
    ] = await Promise.all([
      dashboardService.getOwnerMetrics(),
      dashboardService.getAlerts(),
      dashboardService.getRevenueData(revenuePeriod.value),
      dashboardService.getBranchComparison(branchComparisonPeriod.value),
      dashboardService.getProductPerformance(),
      dashboardService.getPendingApprovals()
    ])

    metrics.value = metricsData
    alerts.value = alertsData
    revenueChartData.value = revenueData
    branches.value = branchesData
    topProducts.value = productsData.top
    bottomProducts.value = productsData.bottom
    pendingApprovals.value = approvalsData
  } catch (error: any) {
    notify('error', 'Failed to load dashboard', error.message)
  } finally {
    refreshing.value = false
  }
}

async function refreshDashboard() {
  await loadDashboardData()
  notify('success', 'Dashboard refreshed')
}

function toggleAutoRefresh() {
  autoRefresh.value = !autoRefresh.value

  if (autoRefresh.value) {
    startAutoRefresh()
  } else {
    stopAutoRefresh()
  }
}

function startAutoRefresh() {
  // Refresh every 30 seconds
  autoRefreshInterval.value = window.setInterval(() => {
    loadDashboardData()
  }, 30000)
}

function stopAutoRefresh() {
  if (autoRefreshInterval.value) {
    clearInterval(autoRefreshInterval.value)
  }
}

function handleDismissAlert(alertId: number) {
  alerts.value = alerts.value.filter(a => a.id !== alertId)
}

function handleApprovalClick(approval: any) {
  router.push(`/approvals/${approval.id}`)
}

function navigateTo(path: string) {
  router.push(path)
}

// Lifecycle
onMounted(() => {
  loadDashboardData()

  if (autoRefresh.value) {
    startAutoRefresh()
  }
})

onUnmounted(() => {
  stopAutoRefresh()
})
</script>

<style scoped>
.owner-dashboard {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Smooth scrolling */
.owner-dashboard {
  scroll-behavior: smooth;
}

/* Ensure cards are touch-friendly */
@media (max-width: 640px) {
  .owner-dashboard .grid {
    gap: 1rem;
  }
}
</style>
