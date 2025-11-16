<template>
  <div class="progress-bar-container">
    <!-- Label (optional) -->
    <div v-if="showLabel" class="flex items-center justify-between mb-2">
      <span class="text-sm font-medium text-gray-700">
        {{ label }}
      </span>
      <span :class="['text-sm font-semibold', percentageClass]">
        {{ formattedPercentage }}
      </span>
    </div>

    <!-- Progress bar -->
    <div
      :class="[
        'progress-bar-track overflow-hidden rounded-full',
        sizeClass,
        trackClass
      ]"
    >
      <div
        :class="[
          'progress-bar-fill h-full transition-all duration-500 ease-out rounded-full',
          colorClass,
          striped && 'progress-bar-striped',
          animated && 'progress-bar-animated'
        ]"
        :style="{ width: `${percentage}%` }"
        role="progressbar"
        :aria-valuenow="percentage"
        aria-valuemin="0"
        aria-valuemax="100"
      ></div>
    </div>

    <!-- Time/Status Info (optional) -->
    <div v-if="showTimeInfo && timeSpent !== undefined && targetTime !== undefined" class="mt-2 flex items-center justify-between text-xs">
      <span class="text-gray-600">
        Time: <span class="font-medium">{{ formatTime(timeSpent) }}</span>
      </span>
      <span :class="timeSpent <= targetTime ? 'text-green-600' : 'text-yellow-600'">
        Target: {{ formatTime(targetTime) }}
        <span v-if="timeSpent < targetTime" class="font-semibold">
          ({{ formatTime(targetTime - timeSpent) }} ahead)
        </span>
        <span v-else-if="timeSpent > targetTime" class="font-semibold">
          ({{ formatTime(timeSpent - targetTime) }} over)
        </span>
      </span>
    </div>

    <!-- Steps indicator (optional) -->
    <div v-if="showSteps && currentStep !== undefined && totalSteps !== undefined" class="mt-2">
      <div class="flex items-center justify-between">
        <div class="flex gap-1">
          <div
            v-for="step in totalSteps"
            :key="step"
            :class="[
              'h-2 rounded-full transition-all duration-300',
              step <= currentStep ? colorClass : 'bg-gray-200',
              'flex-1'
            ]"
            :style="{ width: `${100 / totalSteps}%` }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { formatNumber } from '@/utils/formatters'

type ColorVariant = 'primary' | 'success' | 'warning' | 'danger' | 'info'
type SizeVariant = 'sm' | 'md' | 'lg'

interface Props {
  value?: number
  max?: number
  label?: string
  showLabel?: boolean
  showPercentage?: boolean
  showTimeInfo?: boolean
  showSteps?: boolean
  color?: ColorVariant
  size?: SizeVariant
  striped?: boolean
  animated?: boolean
  timeSpent?: number // in seconds
  targetTime?: number // in seconds
  currentStep?: number
  totalSteps?: number
  trackClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  value: 0,
  max: 100,
  showLabel: true,
  showPercentage: true,
  showTimeInfo: false,
  showSteps: false,
  color: 'primary',
  size: 'md',
  striped: false,
  animated: false
})

// Computed
const percentage = computed(() => {
  const pct = (props.value / props.max) * 100
  return Math.min(100, Math.max(0, pct))
})

const formattedPercentage = computed(() => {
  if (!props.showPercentage) return ''
  return `${Math.round(percentage.value)}%`
})

const colorClass = computed(() => {
  const colors: Record<ColorVariant, string> = {
    primary: 'bg-primary-600',
    success: 'bg-green-600',
    warning: 'bg-yellow-500',
    danger: 'bg-red-600',
    info: 'bg-blue-600'
  }
  return colors[props.color]
})

const percentageClass = computed(() => {
  const colors: Record<ColorVariant, string> = {
    primary: 'text-primary-600',
    success: 'text-green-600',
    warning: 'text-yellow-600',
    danger: 'text-red-600',
    info: 'text-blue-600'
  }
  return colors[props.color]
})

const sizeClass = computed(() => {
  const sizes: Record<SizeVariant, string> = {
    sm: 'h-1',
    md: 'h-2',
    lg: 'h-3'
  }
  return sizes[props.size]
})

// Methods
function formatTime(seconds: number): string {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60

  if (mins === 0) {
    return `${secs}s`
  }

  return `${mins}m ${secs}s`
}
</script>

<style scoped>
.progress-bar-track {
  background-color: #e5e7eb; /* gray-200 */
}

.progress-bar-striped {
  background-image: linear-gradient(
    45deg,
    rgba(255, 255, 255, 0.15) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255, 255, 255, 0.15) 50%,
    rgba(255, 255, 255, 0.15) 75%,
    transparent 75%,
    transparent
  );
  background-size: 1rem 1rem;
}

.progress-bar-animated {
  animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
  0% {
    background-position: 1rem 0;
  }
  100% {
    background-position: 0 0;
  }
}

/* Smooth transition */
.progress-bar-fill {
  transition-property: width, background-color;
}
</style>
