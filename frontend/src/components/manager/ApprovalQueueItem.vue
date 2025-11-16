<template>
  <div
    :class="[
      'approval-queue-item border rounded-lg p-4 transition-all',
      selected ? 'border-primary-500 bg-primary-50' : 'border-gray-200 bg-white hover:border-primary-300'
    ]"
  >
    <div class="flex items-start gap-3">
      <!-- Checkbox -->
      <input
        type="checkbox"
        :checked="selected"
        @change="emit('toggle-select')"
        class="mt-1 h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
      />

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-2">
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <h3 class="text-sm font-semibold text-gray-900">{{ approval.title }}</h3>
              <StatusBadge
                :variant="getTypeColor(approval.type)"
                :label="formatType(approval.type)"
                size="sm"
              />
            </div>
            <p class="text-xs text-gray-500">
              Requested by {{ approval.requested_by }} • {{ formatRelativeTime(new Date(approval.requested_at)) }}
            </p>
          </div>

          <!-- Amount (if applicable) -->
          <div v-if="approval.amount" class="text-right">
            <p class="text-sm font-bold text-gray-900">
              {{ formatCurrency(approval.amount) }}
            </p>
          </div>
        </div>

        <!-- Description -->
        <p class="text-sm text-gray-700 mb-3">
          {{ approval.description }}
        </p>

        <!-- Decision Heuristics -->
        <div v-if="approval.heuristics" class="mb-3 p-3 bg-gray-50 rounded-lg">
          <p class="text-xs font-medium text-gray-700 mb-2">Decision Factors:</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div
              v-for="(heuristic, key) in approval.heuristics"
              :key="key"
              class="flex items-center justify-between text-xs"
            >
              <span class="text-gray-600">{{ formatHeuristicKey(key) }}:</span>
              <span
                :class="[
                  'font-medium',
                  getHeuristicColor(key, heuristic)
                ]"
              >
                {{ formatHeuristicValue(key, heuristic) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Attached evidence (photos, documents) -->
        <div v-if="approval.attachments && approval.attachments.length > 0" class="mb-3 flex items-center gap-2">
          <PhotoIcon class="h-4 w-4 text-gray-400" />
          <button
            @click="viewAttachments"
            class="text-xs font-medium text-primary-600 hover:text-primary-700"
          >
            View {{ approval.attachments.length }} attachment{{ approval.attachments.length > 1 ? 's' : '' }}
          </button>
        </div>

        <!-- Action buttons -->
        <div class="flex items-center gap-2">
          <button
            @click="emit('view')"
            class="text-xs font-medium text-gray-700 px-3 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50"
          >
            View Details
          </button>

          <button
            @click="emit('approve')"
            class="text-xs font-medium text-white px-3 py-1.5 bg-green-600 rounded-md hover:bg-green-700"
          >
            Approve
          </button>

          <button
            @click="emit('reject')"
            class="text-xs font-medium text-white px-3 py-1.5 bg-red-600 rounded-md hover:bg-red-700"
          >
            Reject
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PhotoIcon } from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import { formatCurrency, formatRelativeTime } from '@/utils/formatters'

interface Approval {
  id: number
  type: string
  title: string
  description: string
  amount?: number
  requested_by: string
  requested_at: string
  heuristics?: Record<string, any>
  attachments?: any[]
}

interface Props {
  approval: Approval
  selected: boolean
}

interface Emits {
  (e: 'toggle-select'): void
  (e: 'approve'): void
  (e: 'reject'): void
  (e: 'view'): void
}

defineProps<Props>()
const emit = defineEmits<Emits>()

// Methods
function formatType(type: string): string {
  const types: Record<string, string> = {
    adjustment: 'Stock Adjustment',
    purchase: 'Purchase Order',
    expense: 'Expense',
    void: 'Void Request',
    return: 'Return',
    discount: 'Discount'
  }
  return types[type] || type
}

function getTypeColor(type: string): 'yellow' | 'blue' | 'red' | 'purple' {
  const colors: Record<string, any> = {
    adjustment: 'yellow',
    purchase: 'blue',
    expense: 'red',
    void: 'purple',
    return: 'yellow',
    discount: 'red'
  }
  return colors[type] || 'gray'
}

function formatHeuristicKey(key: string): string {
  return key
    .replace(/_/g, ' ')
    .replace(/\b\w/g, l => l.toUpperCase())
}

function formatHeuristicValue(key: string, value: any): string {
  if (typeof value === 'boolean') {
    return value ? 'Yes' : 'No'
  }

  if (typeof value === 'number') {
    if (key.includes('amount') || key.includes('value')) {
      return formatCurrency(value)
    }
    if (key.includes('percent') || key.includes('rate')) {
      return `${value.toFixed(1)}%`
    }
    return value.toString()
  }

  return String(value)
}

function getHeuristicColor(key: string, value: any): string {
  // Risk indicators
  if (key.includes('risk') || key.includes('variance')) {
    if (typeof value === 'string') {
      if (value === 'high') return 'text-red-600'
      if (value === 'medium') return 'text-yellow-600'
      return 'text-green-600'
    }
    if (typeof value === 'number') {
      if (value > 10) return 'text-red-600'
      if (value > 5) return 'text-yellow-600'
      return 'text-green-600'
    }
  }

  // Boolean indicators
  if (typeof value === 'boolean') {
    return value ? 'text-green-600' : 'text-gray-600'
  }

  return 'text-gray-900'
}

function viewAttachments() {
  // TODO: Open attachments viewer
}
</script>

<style scoped>
.approval-queue-item {
  transition-property: border-color, background-color, box-shadow;
}

.approval-queue-item:hover {
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

/* Touch-friendly buttons */
@media (max-width: 640px) {
  .approval-queue-item button {
    min-height: 44px;
  }
}
</style>
