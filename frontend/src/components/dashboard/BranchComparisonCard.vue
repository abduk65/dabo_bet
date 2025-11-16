<template>
  <div
    :class="[
      'branch-comparison-card bg-white border rounded-lg p-5 transition-all duration-200',
      clickable && 'cursor-pointer hover:shadow-md hover:border-primary-300',
      isTopPerformer && 'border-green-300 bg-green-50',
      isBottomPerformer && 'border-red-300 bg-red-50'
    ]"
    @click="handleClick"
  >
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <h3 class="text-base font-semibold text-gray-900">{{ branch.name }}</h3>
          <StatusBadge
            v-if="isTopPerformer"
            label="Top"
            variant="green"
            size="sm"
          />
          <StatusBadge
            v-if="isBottomPerformer"
            label="Needs Attention"
            variant="red"
            size="sm"
          />
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ branch.location }}</p>
      </div>

      <!-- Branch icon/image -->
      <div class="flex-shrink-0">
        <div v-if="branch.image" class="h-10 w-10 rounded-lg overflow-hidden">
          <img :src="branch.image" :alt="branch.name" class="h-full w-full object-cover" />
        </div>
        <div v-else class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center">
          <BuildingStorefrontIcon class="h-6 w-6 text-primary-600" />
        </div>
      </div>
    </div>

    <!-- Metrics -->
    <div class="space-y-3">
      <!-- Revenue -->
      <div>
        <div class="flex items-center justify-between mb-1">
          <span class="text-xs font-medium text-gray-600">Revenue</span>
          <div class="flex items-center gap-1">
            <component
              :is="revenueTrendIcon"
              :class="['h-3 w-3', revenueTrendColor]"
            />
            <span :class="['text-xs font-semibold', revenueTrendColor]">
              {{ formatTrendPercentage(branch.revenue_trend) }}
            </span>
          </div>
        </div>
        <p class="text-lg font-bold text-gray-900">
          {{ formatCurrency(branch.revenue) }}
        </p>
        <ProgressBar
          :value="branch.revenue"
          :max="maxRevenue"
          :color="getPerformanceColor(branch.revenue_trend)"
          size="sm"
          :show-label="false"
          :show-percentage="false"
        />
      </div>

      <!-- Orders -->
      <div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-gray-600">Orders</span>
          <span class="font-semibold text-gray-900">{{ formatNumber(branch.orders) }}</span>
        </div>
      </div>

      <!-- Average Order Value -->
      <div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-gray-600">Avg Order</span>
          <span class="font-semibold text-gray-900">
            {{ formatCurrency(branch.avg_order_value) }}
          </span>
        </div>
      </div>

      <!-- Profit Margin -->
      <div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-gray-600">Profit Margin</span>
          <span
            :class="[
              'font-semibold',
              branch.profit_margin >= 25 ? 'text-green-600' :
              branch.profit_margin >= 15 ? 'text-yellow-600' :
              'text-red-600'
            ]"
          >
            {{ branch.profit_margin.toFixed(1) }}%
          </span>
        </div>
      </div>
    </div>

    <!-- Footer - Comparison period -->
    <div class="mt-4 pt-4 border-t border-gray-200">
      <div class="flex items-center justify-between text-xs text-gray-500">
        <span>{{ comparisonPeriodLabel }}</span>
        <ChevronRightIcon v-if="clickable" class="h-4 w-4" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {
  BuildingStorefrontIcon,
  ArrowUpIcon,
  ArrowDownIcon,
  ChevronRightIcon
} from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import ProgressBar from '@/components/common/ProgressBar.vue'
import { formatCurrency, formatNumber } from '@/utils/formatters'

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

interface Props {
  branch: Branch
  comparisonPeriod?: 'today' | 'week' | 'month'
  maxRevenue?: number
  clickable?: boolean
  topPerformerThreshold?: number
  bottomPerformerThreshold?: number
}

interface Emits {
  (e: 'click', branch: Branch): void
}

const props = withDefaults(defineProps<Props>(), {
  comparisonPeriod: 'today',
  clickable: true,
  topPerformerThreshold: 20,
  bottomPerformerThreshold: -10
})

const emit = defineEmits<Emits>()

// Computed
const comparisonPeriodLabel = computed(() => {
  const labels = {
    today: 'vs. Yesterday',
    week: 'vs. Last Week',
    month: 'vs. Last Month'
  }
  return labels[props.comparisonPeriod]
})

const isTopPerformer = computed(() => {
  return props.branch.revenue_trend >= props.topPerformerThreshold
})

const isBottomPerformer = computed(() => {
  return props.branch.revenue_trend <= props.bottomPerformerThreshold
})

const revenueTrendIcon = computed(() => {
  return props.branch.revenue_trend >= 0 ? ArrowUpIcon : ArrowDownIcon
})

const revenueTrendColor = computed(() => {
  return props.branch.revenue_trend >= 0 ? 'text-green-600' : 'text-red-600'
})

// Methods
function getPerformanceColor(trend: number): 'success' | 'warning' | 'danger' {
  if (trend >= 10) return 'success'
  if (trend >= 0) return 'warning'
  return 'danger'
}

function formatTrendPercentage(value: number): string {
  const sign = value > 0 ? '+' : ''
  return `${sign}${value.toFixed(1)}%`
}

function handleClick() {
  if (props.clickable) {
    emit('click', props.branch)
  }
}
</script>

<style scoped>
.branch-comparison-card {
  transition-property: transform, box-shadow, border-color;
}

.branch-comparison-card.cursor-pointer:hover {
  transform: translateY(-2px);
}

/* Animation */
.branch-comparison-card {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
