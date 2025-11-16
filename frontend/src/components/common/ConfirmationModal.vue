<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="emit('cancel')">
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
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <!-- Icon -->
                  <div
                    :class="[
                      'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10',
                      iconBgClass
                    ]"
                  >
                    <component :is="icon" :class="['h-6 w-6', iconColorClass]" aria-hidden="true" />
                  </div>

                  <!-- Content -->
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                      {{ title }}
                    </DialogTitle>

                    <div class="mt-2">
                      <p class="text-sm text-gray-500">
                        {{ message }}
                      </p>

                      <!-- Additional content slot -->
                      <div v-if="$slots.default" class="mt-4">
                        <slot></slot>
                      </div>

                      <!-- Reason input (if required) -->
                      <div v-if="requireReason" class="mt-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                          {{ reasonLabel }} *
                        </label>
                        <textarea
                          id="reason"
                          v-model="reason"
                          rows="3"
                          :placeholder="reasonPlaceholder"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                        ></textarea>
                      </div>

                      <!-- Checkbox confirmation (if required) -->
                      <div v-if="requireCheckbox" class="mt-4">
                        <label class="flex items-start gap-2 cursor-pointer">
                          <input
                            v-model="confirmed"
                            type="checkbox"
                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                          />
                          <span class="text-sm text-gray-700">
                            {{ checkboxLabel }}
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <button
                  @click="handleConfirm"
                  type="button"
                  :disabled="!canConfirm"
                  :class="[
                    'inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto',
                    confirmButtonClass,
                    !canConfirm && 'opacity-50 cursor-not-allowed'
                  ]"
                >
                  {{ confirmText }}
                </button>
                <button
                  @click="emit('cancel')"
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                >
                  {{ cancelText }}
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
import { ref, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import {
  ExclamationTriangleIcon,
  CheckCircleIcon,
  InformationCircleIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'
import type { Component } from 'vue'

type Variant = 'warning' | 'success' | 'danger' | 'info'

interface Props {
  open: boolean
  title: string
  message: string
  confirmText?: string
  cancelText?: string
  confirmVariant?: Variant
  icon?: Component
  requireReason?: boolean
  reasonLabel?: string
  reasonPlaceholder?: string
  requireCheckbox?: boolean
  checkboxLabel?: string
}

interface Emits {
  (e: 'confirm', data?: any): void
  (e: 'cancel'): void
}

const props = withDefaults(defineProps<Props>(), {
  confirmText: 'Confirm',
  cancelText: 'Cancel',
  confirmVariant: 'warning',
  reasonLabel: 'Reason',
  reasonPlaceholder: 'Please provide a reason...',
  checkboxLabel: 'I understand and want to proceed'
})

const emit = defineEmits<Emits>()

// State
const reason = ref('')
const confirmed = ref(false)

// Computed
const icon = computed(() => {
  if (props.icon) return props.icon

  const icons: Record<Variant, Component> = {
    warning: ExclamationTriangleIcon,
    success: CheckCircleIcon,
    danger: XCircleIcon,
    info: InformationCircleIcon
  }

  return icons[props.confirmVariant]
})

const iconBgClass = computed(() => {
  const classes: Record<Variant, string> = {
    warning: 'bg-yellow-100',
    success: 'bg-green-100',
    danger: 'bg-red-100',
    info: 'bg-blue-100'
  }

  return classes[props.confirmVariant]
})

const iconColorClass = computed(() => {
  const classes: Record<Variant, string> = {
    warning: 'text-yellow-600',
    success: 'text-green-600',
    danger: 'text-red-600',
    info: 'text-blue-600'
  }

  return classes[props.confirmVariant]
})

const confirmButtonClass = computed(() => {
  const classes: Record<Variant, string> = {
    warning: 'bg-yellow-600 hover:bg-yellow-700',
    success: 'bg-green-600 hover:bg-green-700',
    danger: 'bg-red-600 hover:bg-red-700',
    info: 'bg-blue-600 hover:bg-blue-700'
  }

  return classes[props.confirmVariant]
})

const canConfirm = computed(() => {
  if (props.requireReason && !reason.value.trim()) {
    return false
  }

  if (props.requireCheckbox && !confirmed.value) {
    return false
  }

  return true
})

// Methods
function handleConfirm() {
  if (!canConfirm.value) return

  if (props.requireReason) {
    emit('confirm', reason.value)
  } else {
    emit('confirm')
  }

  // Reset state
  reason.value = ''
  confirmed.value = false
}
</script>
