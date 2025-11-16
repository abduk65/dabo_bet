<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="emit('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
              <!-- Header -->
              <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <DialogTitle class="text-lg font-semibold text-gray-900">
                      {{ approval.title }}
                    </DialogTitle>
                    <p class="mt-1 text-sm text-gray-500">
                      Requested by {{ approval.requested_by }} • {{ formatDateTime(new Date(approval.requested_at)) }}
                    </p>
                  </div>

                  <button
                    @click="emit('close')"
                    class="ml-4 rounded-md text-gray-400 hover:text-gray-500"
                  >
                    <XMarkIcon class="h-6 w-6" />
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                <!-- Type and amount -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                    <StatusBadge
                      :variant="getTypeColor(approval.type)"
                      :label="formatType(approval.type)"
                    />
                  </div>

                  <div v-if="approval.amount">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Amount</label>
                    <p class="text-xl font-bold text-gray-900">
                      {{ formatCurrency(approval.amount) }}
                    </p>
                  </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                  <p class="text-sm text-gray-900">{{ approval.description }}</p>
                </div>

                <!-- Decision Heuristics -->
                <div v-if="approval.heuristics" class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Decision Factors</label>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div
                      v-for="(value, key) in approval.heuristics"
                      :key="key"
                      class="bg-gray-50 rounded-lg p-3"
                    >
                      <p class="text-xs font-medium text-gray-500 mb-1">
                        {{ formatHeuristicKey(key) }}
                      </p>
                      <p :class="['text-sm font-semibold', getHeuristicColor(key, value)]">
                        {{ formatHeuristicValue(key, value) }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Related data (context) -->
                <div v-if="approval.context" class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Related Information</label>
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                      <div v-for="(value, key) in approval.context" :key="key">
                        <span class="text-blue-700 font-medium">{{ formatContextKey(key) }}:</span>
                        <span class="ml-2 text-blue-900">{{ value }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Attachments -->
                <div v-if="approval.attachments && approval.attachments.length > 0" class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Attachments</label>
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div
                      v-for="(attachment, index) in approval.attachments"
                      :key="index"
                      class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 cursor-pointer hover:border-primary-500"
                      @click="viewAttachment(attachment)"
                    >
                      <img
                        v-if="attachment.type === 'image'"
                        :src="attachment.url"
                        :alt="`Attachment ${index + 1}`"
                        class="h-full w-full object-cover"
                      />
                      <div v-else class="h-full w-full bg-gray-100 flex items-center justify-center">
                        <DocumentIcon class="h-8 w-8 text-gray-400" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Notes/Comments input -->
                <div class="mb-6">
                  <label for="approval-notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes (Optional)
                  </label>
                  <textarea
                    id="approval-notes"
                    v-model="notes"
                    rows="3"
                    placeholder="Add any notes or comments..."
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  ></textarea>
                </div>

                <!-- Rejection reason (only shown when rejecting) -->
                <div v-if="showRejectReason" class="mb-6">
                  <label for="reject-reason" class="block text-sm font-medium text-red-700 mb-2">
                    Rejection Reason *
                  </label>
                  <textarea
                    id="reject-reason"
                    v-model="rejectReason"
                    rows="3"
                    required
                    placeholder="Please provide a reason for rejection..."
                    class="block w-full px-3 py-2 border border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                  ></textarea>
                </div>
              </div>

              <!-- Footer -->
              <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
                <button
                  @click="emit('close')"
                  type="button"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>

                <button
                  v-if="!showRejectReason"
                  @click="handleReject"
                  type="button"
                  class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                >
                  Reject
                </button>

                <button
                  v-if="!showRejectReason"
                  @click="handleApprove"
                  type="button"
                  :disabled="processing"
                  class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50"
                >
                  {{ processing ? 'Processing...' : 'Approve' }}
                </button>

                <button
                  v-else
                  @click="confirmReject"
                  type="button"
                  :disabled="!rejectReason || processing"
                  class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50"
                >
                  {{ processing ? 'Processing...' : 'Confirm Rejection' }}
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { XMarkIcon, DocumentIcon } from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import { formatCurrency, formatDateTime } from '@/utils/formatters'

interface Approval {
  id: number
  type: string
  title: string
  description: string
  amount?: number
  requested_by: string
  requested_at: string
  heuristics?: Record<string, any>
  context?: Record<string, any>
  attachments?: any[]
}

interface Props {
  approval: Approval
  open: boolean
}

interface Emits {
  (e: 'close'): void
  (e: 'approve', approval: Approval, notes?: string): void
  (e: 'reject', approval: Approval, reason: string): void
}

defineProps<Props>()
const emit = defineEmits<Emits>()

// State
const notes = ref('')
const rejectReason = ref('')
const showRejectReason = ref(false)
const processing = ref(false)

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

function getTypeColor(type: string): any {
  const colors: Record<string, any> = {
    adjustment: 'yellow',
    purchase: 'blue',
    expense: 'red',
    void: 'purple'
  }
  return colors[type] || 'gray'
}

function formatHeuristicKey(key: string): string {
  return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

function formatHeuristicValue(key: string, value: any): string {
  if (typeof value === 'boolean') return value ? 'Yes' : 'No'
  if (typeof value === 'number') {
    if (key.includes('amount')) return formatCurrency(value)
    if (key.includes('percent')) return `${value.toFixed(1)}%`
    return value.toString()
  }
  return String(value)
}

function getHeuristicColor(key: string, value: any): string {
  if (key.includes('risk') || key.includes('variance')) {
    if (typeof value === 'string') {
      if (value === 'high') return 'text-red-600'
      if (value === 'medium') return 'text-yellow-600'
      return 'text-green-600'
    }
  }
  return 'text-gray-900'
}

function formatContextKey(key: string): string {
  return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

function viewAttachment(attachment: any) {
  window.open(attachment.url, '_blank')
}

function handleApprove() {
  processing.value = true
  emit('approve', approval, notes.value || undefined)
  processing.value = false
}

function handleReject() {
  showRejectReason.value = true
}

function confirmReject() {
  if (!rejectReason.value) return

  processing.value = true
  emit('reject', approval, rejectReason.value)
  processing.value = false
}
</script>

<style scoped>
/* Ensure modal content is scrollable */
.max-h-\\[60vh\\] {
  max-height: 60vh;
}

/* Smooth transitions */
.approval-modal {
  transition: all 0.3s ease-in-out;
}
</style>
