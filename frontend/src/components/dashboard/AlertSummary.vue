<template>
  <div class="alert-summary bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <BellAlertIcon class="h-5 w-5 text-yellow-600" />
          <h3 class="text-base font-semibold text-gray-900">Alerts</h3>
          <StatusBadge :label="String(alerts.length)" variant="yellow" size="sm" />
        </div>

        <button
          @click="emit('view-all')"
          class="text-sm font-medium text-primary-600 hover:text-primary-700"
        >
          View all
        </button>
      </div>
    </div>

    <div class="divide-y divide-gray-200">
      <div
        v-for="alert in sortedAlerts.slice(0, maxVisible)"
        :key="alert.id"
        :class="[
          'px-6 py-4 flex items-start gap-4 transition-colors',
          alert.severity === 'critical' && 'bg-red-50',
          alert.severity === 'high' && 'bg-orange-50',
          alert.severity === 'medium' && 'bg-yellow-50',
          'hover:bg-opacity-75'
        ]"
      >
        <!-- Severity indicator -->
        <div class="flex-shrink-0">
          <div
            :class="[
              'h-10 w-10 rounded-full flex items-center justify-center',
              getSeverityColor(alert.severity).bg
            ]"
          >
            <component
              :is="getSeverityIcon(alert.type)"
              :class="['h-6 w-6', getSeverityColor(alert.severity).text]"
            />
          </div>
        </div>

        <!-- Alert content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
              <p :class="['text-sm font-semibold', getSeverityColor(alert.severity).text]">
                {{ alert.title }}
              </p>
              <p class="text-sm text-gray-700 mt-1">
                {{ alert.message }}
              </p>

              <!-- Meta information -->
              <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1">
                  <ClockIcon class="h-3 w-3" />
                  {{ formatRelativeTime(new Date(alert.created_at)) }}
                </span>
                <span v-if="alert.location" class="flex items-center gap-1">
                  <MapPinIcon class="h-3 w-3" />
                  {{ alert.location }}
                </span>
              </div>

              <!-- Action buttons -->
              <div v-if="alert.actions" class="mt-3 flex items-center gap-2">
                <button
                  v-for="action in alert.actions"
                  :key="action.label"
                  @click="handleAction(alert, action)"
                  :class="[
                    'text-xs font-medium px-3 py-1 rounded-md',
                    action.primary
                      ? 'bg-primary-600 text-white hover:bg-primary-700'
                      : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                  ]"
                >
                  {{ action.label }}
                </button>
              </div>
            </div>

            <!-- Dismiss button -->
            <button
              v-if="dismissable"
              @click="handleDismiss(alert)"
              class="flex-shrink-0 p-1 rounded hover:bg-gray-100"
            >
              <XMarkIcon class="h-5 w-5 text-gray-400" />
            </button>
          </div>
        </div>
      </div>

      <!-- Show more -->
      <div v-if="alerts.length > maxVisible" class="px-6 py-3 bg-gray-50 text-center">
        <button
          @click="showAll = !showAll"
          class="text-sm font-medium text-primary-600 hover:text-primary-700"
        >
          {{ showAll ? 'Show less' : `Show ${alerts.length - maxVisible} more` }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  BellAlertIcon,
  ExclamationTriangleIcon,
  ExclamationCircleIcon,
  InformationCircleIcon,
  ShieldExclamationIcon,
  ClockIcon,
  MapPinIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import { formatRelativeTime } from '@/utils/formatters'

type AlertSeverity = 'critical' | 'high' | 'medium' | 'low'
type AlertType = 'stock' | 'finance' | 'security' | 'system' | 'quality' | 'sales'

interface AlertAction {
  label: string
  action: string
  primary?: boolean
}

interface Alert {
  id: number
  type: AlertType
  severity: AlertSeverity
  title: string
  message: string
  created_at: string
  location?: string
  actions?: AlertAction[]
}

interface Props {
  alerts: Alert[]
  maxVisible?: number
  dismissable?: boolean
}

interface Emits {
  (e: 'dismiss', alertId: number): void
  (e: 'action', alert: Alert, action: AlertAction): void
  (e: 'view-all'): void
}

const props = withDefaults(defineProps<Props>(), {
  maxVisible: 5,
  dismissable: true
})

const emit = defineEmits<Emits>()

// State
const showAll = ref(false)

// Computed
const sortedAlerts = computed(() => {
  // Sort by severity (critical > high > medium > low) and then by created_at
  const severityOrder = { critical: 0, high: 1, medium: 2, low: 3 }

  return [...props.alerts].sort((a, b) => {
    const severityDiff = severityOrder[a.severity] - severityOrder[b.severity]
    if (severityDiff !== 0) return severityDiff

    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
  })
})

// Methods
function getSeverityColor(severity: AlertSeverity) {
  const colors = {
    critical: {
      bg: 'bg-red-100',
      text: 'text-red-800',
      border: 'border-red-300'
    },
    high: {
      bg: 'bg-orange-100',
      text: 'text-orange-800',
      border: 'border-orange-300'
    },
    medium: {
      bg: 'bg-yellow-100',
      text: 'text-yellow-800',
      border: 'border-yellow-300'
    },
    low: {
      bg: 'bg-blue-100',
      text: 'text-blue-800',
      border: 'border-blue-300'
    }
  }

  return colors[severity]
}

function getSeverityIcon(type: AlertType) {
  const icons = {
    stock: ExclamationTriangleIcon,
    finance: ExclamationCircleIcon,
    security: ShieldExclamationIcon,
    system: InformationCircleIcon,
    quality: ExclamationTriangleIcon,
    sales: InformationCircleIcon
  }

  return icons[type] || InformationCircleIcon
}

function handleDismiss(alert: Alert) {
  emit('dismiss', alert.id)
}

function handleAction(alert: Alert, action: AlertAction) {
  emit('action', alert, action)
}
</script>

<style scoped>
.alert-summary {
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Smooth transitions */
.alert-summary > div > div {
  transition: background-color 150ms ease-in-out;
}

/* Touch-friendly on mobile */
@media (max-width: 640px) {
  .alert-summary button {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
