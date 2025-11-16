<template>
  <div
    :class="[
      'metric-card rounded-lg border p-6 transition-all duration-200',
      clickable && 'cursor-pointer hover:shadow-md hover:border-primary-300',
      variantClass,
      cardClass
    ]"
    @click="handleClick"
  >
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <!-- Label -->
        <p :class="['text-sm font-medium', labelColorClass]">
          {{ label }}
        </p>

        <!-- Value -->
        <div class="mt-2 flex items-baseline">
          <p :class="['text-3xl font-bold', valueColorClass]">
            {{ formattedValue }}
          </p>
          <p v-if="unit" :class="['ml-2 text-lg font-medium', unitColorClass]">
            {{ unit }}
          </p>
        </div>

        <!-- Change/Trend -->
        <div v-if="showTrend && (change !== undefined || trendPercentage !== undefined)" class="mt-2 flex items-center">
          <component
            v-if="trendIcon"
            :is="trendIcon"
            :class="['h-4 w-4 mr-1', trendColorClass]"
          />
          <span :class="['text-sm font-medium', trendColorClass]">
            <span v-if="trendPercentage !== undefined">
              {{ trendPercentage > 0 ? '+' : '' }}{{ trendPercentage.toFixed(1) }}%
            </span>
            <span v-if="change !== undefined" class="ml-1">
              ({{ change > 0 ? '+' : '' }}{{ formatCurrency ? formatCurrency(change) : change }})
            </span>
          </span>
          <span v-if="trendLabel" class="ml-2 text-sm text-gray-500">
            {{ trendLabel }}
          </span>
        </div>

        <!-- Subtitle/Description -->
        <p v-if="subtitle" class="mt-1 text-sm text-gray-600">
          {{ subtitle }}
        </p>
      </div>

      <!-- Icon -->
      <div v-if="icon" :class="['flex-shrink-0 p-3 rounded-lg', iconBgClass]">
        <component :is="icon" :class="['h-6 w-6', iconColorClass]" />
      </div>
    </div>

    <!-- Progress bar (optional) -->
    <div v-if="showProgress && progress !== undefined" class="mt-4">
      <div class="flex items-center justify-between text-sm mb-1">
        <span class="text-gray-600">{{ progressLabel }}</span>
        <span class="font-medium">{{ Math.round(progress) }}%</span>
      </div>
      <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
        <div
          :class="['h-full rounded-full transition-all duration-500', progressColorClass]"
          :style="{ width: `${progress}%` }"
        ></div>
      </div>
    </div>

    <!-- Footer action/link -->
    <div v-if="$slots.footer || footerText" class="mt-4 pt-4 border-t border-gray-200">
      <slot name="footer">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">{{ footerText }}</span>
          <ChevronRightIcon v-if="clickable" class="h-4 w-4 text-gray-400" />
        </div>
      </slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Component } from 'vue'
import { ChevronRightIcon, ArrowUpIcon, ArrowDownIcon } from '@heroicons/vue/24/outline'
import { formatCurrency as formatCurrencyUtil } from '@/utils/formatters'

type Variant = 'default' | 'primary' | 'success' | 'warning' | 'danger' | 'info'

interface Props {
  label: string
  value: number | string
  unit?: string
  subtitle?: string
  icon?: Component
  variant?: Variant
  change?: number
  trendPercentage?: number
  trendLabel?: string
  showTrend?: boolean
  showProgress?: boolean
  progress?: number
  progressLabel?: string
  footerText?: string
  clickable?: boolean
  formatCurrency?: (value: number) => string
  cardClass?: string
}

interface Emits {
  (e: 'click'): void
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  showTrend: true,
  showProgress: false,
  clickable: false,
  formatCurrency: formatCurrencyUtil
})

const emit = defineEmits<Emits>()

// Computed
const formattedValue = computed(() => {
  if (typeof props.value === 'number' && props.formatCurrency) {
    return props.formatCurrency(props.value)
  }
  return props.value
})

const variantClass = computed(() => {
  const variants: Record<Variant, string> = {
    default: 'bg-white border-gray-200',
    primary: 'bg-primary-50 border-primary-200',
    success: 'bg-green-50 border-green-200',
    warning: 'bg-yellow-50 border-yellow-200',
    danger: 'bg-red-50 border-red-200',
    info: 'bg-blue-50 border-blue-200'
  }
  return variants[props.variant]
})

const labelColorClass = computed(() => {
  const colors: Record<Variant, string> = {
    default: 'text-gray-600',
    primary: 'text-primary-700',
    success: 'text-green-700',
    warning: 'text-yellow-700',
    danger: 'text-red-700',
    info: 'text-blue-700'
  }
  return colors[props.variant]
})

const valueColorClass = computed(() => {
  const colors: Record<Variant, string> = {
    default: 'text-gray-900',
    primary: 'text-primary-900',
    success: 'text-green-900',
    warning: 'text-yellow-900',
    danger: 'text-red-900',
    info: 'text-blue-900'
  }
  return colors[props.variant]
})

const unitColorClass = computed(() => {
  return 'text-gray-500'
})

const iconBgClass = computed(() => {
  const colors: Record<Variant, string> = {
    default: 'bg-gray-100',
    primary: 'bg-primary-100',
    success: 'bg-green-100',
    warning: 'bg-yellow-100',
    danger: 'bg-red-100',
    info: 'bg-blue-100'
  }
  return colors[props.variant]
})

const iconColorClass = computed(() => {
  const colors: Record<Variant, string> = {
    default: 'text-gray-600',
    primary: 'text-primary-600',
    success: 'text-green-600',
    warning: 'text-yellow-600',
    danger: 'text-red-600',
    info: 'text-blue-600'
  }
  return colors[props.variant]
})

const trendIcon = computed(() => {
  if (props.trendPercentage === undefined && props.change === undefined) return null

  const value = props.trendPercentage ?? props.change ?? 0
  return value >= 0 ? ArrowUpIcon : ArrowDownIcon
})

const trendColorClass = computed(() => {
  const value = props.trendPercentage ?? props.change ?? 0
  return value >= 0 ? 'text-green-600' : 'text-red-600'
})

const progressColorClass = computed(() => {
  const colors: Record<Variant, string> = {
    default: 'bg-gray-600',
    primary: 'bg-primary-600',
    success: 'bg-green-600',
    warning: 'bg-yellow-600',
    danger: 'bg-red-600',
    info: 'bg-blue-600'
  }
  return colors[props.variant]
})

// Methods
function handleClick() {
  if (props.clickable) {
    emit('click')
  }
}
</script>

<style scoped>
.metric-card {
  min-height: 140px;
}

/* Hover effect for clickable cards */
.metric-card.cursor-pointer:hover {
  transform: translateY(-2px);
}

/* Smooth transitions */
.metric-card {
  transition-property: transform, box-shadow, border-color;
}
</style>
