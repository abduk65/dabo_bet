<template>
  <span
    :class="[
      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
      badgeClass,
      sizeClass,
      dotPosition && 'pl-2'
    ]"
  >
    <svg
      v-if="showDot"
      :class="[dotClass, dotPosition === 'left' ? 'mr-1.5' : 'ml-1.5', dotSizeClass]"
      fill="currentColor"
      viewBox="0 0 8 8"
    >
      <circle cx="4" cy="4" r="3" />
    </svg>

    <component
      v-if="icon"
      :is="icon"
      :class="['h-4 w-4', label && 'mr-1']"
    />

    <slot>{{ label }}</slot>
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Component } from 'vue'
import { STATUS_COLORS, PAYMENT_METHOD_COLORS, ACCOUNT_TYPE_COLORS } from '@/utils/constants'

type StatusType = keyof typeof STATUS_COLORS
type PaymentMethodType = keyof typeof PAYMENT_METHOD_COLORS
type AccountTypeType = keyof typeof ACCOUNT_TYPE_COLORS
type CustomColor = 'primary' | 'secondary' | 'gray' | 'red' | 'yellow' | 'green' | 'blue' | 'indigo' | 'purple' | 'pink'
type SizeVariant = 'sm' | 'md' | 'lg'

interface Props {
  status?: StatusType
  paymentMethod?: PaymentMethodType
  accountType?: AccountTypeType
  variant?: CustomColor
  label?: string
  showDot?: boolean
  dotPosition?: 'left' | 'right'
  size?: SizeVariant
  icon?: Component
}

const props = withDefaults(defineProps<Props>(), {
  showDot: false,
  dotPosition: 'left',
  size: 'md'
})

// Computed
const badgeClass = computed(() => {
  // Priority: status > paymentMethod > accountType > variant
  if (props.status) {
    return STATUS_COLORS[props.status] || 'badge-gray'
  }

  if (props.paymentMethod) {
    return PAYMENT_METHOD_COLORS[props.paymentMethod] || 'badge-gray'
  }

  if (props.accountType) {
    return ACCOUNT_TYPE_COLORS[props.accountType] || 'badge-gray'
  }

  if (props.variant) {
    return `badge-${props.variant}`
  }

  return 'badge-gray'
})

const sizeClass = computed(() => {
  const sizes: Record<SizeVariant, string> = {
    sm: 'text-xs px-2 py-0.5',
    md: 'text-sm px-2.5 py-0.5',
    lg: 'text-base px-3 py-1'
  }
  return sizes[props.size]
})

const dotSizeClass = computed(() => {
  const sizes: Record<SizeVariant, string> = {
    sm: 'h-1.5 w-1.5',
    md: 'h-2 w-2',
    lg: 'h-2.5 w-2.5'
  }
  return sizes[props.size]
})

const dotClass = computed(() => {
  // Dot inherits badge color
  return ''
})
</script>

<style scoped>
/* Badge color variants */
.badge-primary {
  @apply bg-primary-100 text-primary-800;
}

.badge-secondary {
  @apply bg-gray-100 text-gray-800;
}

.badge-gray {
  @apply bg-gray-100 text-gray-700;
}

.badge-red {
  @apply bg-red-100 text-red-800;
}

.badge-yellow {
  @apply bg-yellow-100 text-yellow-800;
}

.badge-green {
  @apply bg-green-100 text-green-800;
}

.badge-blue {
  @apply bg-blue-100 text-blue-800;
}

.badge-indigo {
  @apply bg-indigo-100 text-indigo-800;
}

.badge-purple {
  @apply bg-purple-100 text-purple-800;
}

.badge-pink {
  @apply bg-pink-100 text-pink-800;
}

/* Status-specific badges (from constants) */
.badge-success {
  @apply bg-green-100 text-green-800;
}

.badge-danger {
  @apply bg-red-100 text-red-800;
}

.badge-warning {
  @apply bg-yellow-100 text-yellow-800;
}

.badge-info {
  @apply bg-blue-100 text-blue-800;
}
</style>
