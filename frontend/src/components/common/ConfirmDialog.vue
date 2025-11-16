<template>
  <TransitionRoot as="template" :show="show">
    <Dialog as="div" class="relative z-50" @close="handleCancel">
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
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                  :class="iconBgClass"
                >
                  <component :is="icon" class="h-6 w-6" :class="iconColorClass" />
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                  <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                    {{ title }}
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                <button
                  type="button"
                  class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto"
                  :class="confirmButtonClass"
                  @click="handleConfirm"
                  :disabled="loading"
                >
                  <span v-if="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                  {{ confirmText }}
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                  @click="handleCancel"
                  :disabled="loading"
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
import { computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon, InformationCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

interface Props {
  show: boolean
  title: string
  message: string
  type?: 'warning' | 'info' | 'success' | 'danger'
  confirmText?: string
  cancelText?: string
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'warning',
  confirmText: 'Confirm',
  cancelText: 'Cancel',
  loading: false
})

const emit = defineEmits<{
  (e: 'confirm'): void
  (e: 'cancel'): void
  (e: 'update:show', value: boolean): void
}>()

const icon = computed(() => {
  const icons = {
    warning: ExclamationTriangleIcon,
    info: InformationCircleIcon,
    success: CheckCircleIcon,
    danger: ExclamationTriangleIcon
  }
  return icons[props.type]
})

const iconBgClass = computed(() => {
  const classes = {
    warning: 'bg-yellow-100',
    info: 'bg-blue-100',
    success: 'bg-green-100',
    danger: 'bg-red-100'
  }
  return classes[props.type]
})

const iconColorClass = computed(() => {
  const classes = {
    warning: 'text-yellow-600',
    info: 'text-blue-600',
    success: 'text-green-600',
    danger: 'text-red-600'
  }
  return classes[props.type]
})

const confirmButtonClass = computed(() => {
  const classes = {
    warning: 'bg-yellow-600 hover:bg-yellow-500',
    info: 'bg-blue-600 hover:bg-blue-500',
    success: 'bg-green-600 hover:bg-green-500',
    danger: 'bg-red-600 hover:bg-red-500'
  }
  return classes[props.type]
})

function handleConfirm() {
  emit('confirm')
}

function handleCancel() {
  emit('cancel')
  emit('update:show', false)
}
</script>
