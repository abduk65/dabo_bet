<template>
  <TransitionGroup
    tag="div"
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
    class="fixed top-4 right-4 z-50 space-y-4 max-w-sm w-full pointer-events-none"
  >
    <div
      v-for="notification in notifications"
      :key="notification.id"
      :class="[
        'pointer-events-auto w-full overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5',
        getBackgroundClass(notification.type)
      ]"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <CheckCircleIcon v-if="notification.type === 'success'" class="h-6 w-6 text-green-400" />
            <ExclamationCircleIcon v-else-if="notification.type === 'error'" class="h-6 w-6 text-red-400" />
            <ExclamationTriangleIcon v-else-if="notification.type === 'warning'" class="h-6 w-6 text-yellow-400" />
            <InformationCircleIcon v-else class="h-6 w-6 text-blue-400" />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p :class="['text-sm font-medium', getTextClass(notification.type)]">
              {{ notification.title }}
            </p>
            <p v-if="notification.message" :class="['mt-1 text-sm', getSubTextClass(notification.type)]">
              {{ notification.message }}
            </p>
          </div>
          <div class="ml-4 flex flex-shrink-0">
            <button
              type="button"
              @click="remove(notification.id)"
              :class="[
                'inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2',
                getButtonClass(notification.type)
              ]"
            >
              <span class="sr-only">Close</span>
              <XMarkIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionGroup>
</template>

<script setup lang="ts">
import { useNotification, type NotificationType } from '@/composables/useNotification'
import {
  CheckCircleIcon,
  ExclamationCircleIcon,
  ExclamationTriangleIcon,
  InformationCircleIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

const { notifications, remove } = useNotification()

function getBackgroundClass(type: NotificationType): string {
  const classes = {
    success: 'bg-green-50',
    error: 'bg-red-50',
    warning: 'bg-yellow-50',
    info: 'bg-blue-50'
  }
  return classes[type]
}

function getTextClass(type: NotificationType): string {
  const classes = {
    success: 'text-green-800',
    error: 'text-red-800',
    warning: 'text-yellow-800',
    info: 'text-blue-800'
  }
  return classes[type]
}

function getSubTextClass(type: NotificationType): string {
  const classes = {
    success: 'text-green-700',
    error: 'text-red-700',
    warning: 'text-yellow-700',
    info: 'text-blue-700'
  }
  return classes[type]
}

function getButtonClass(type: NotificationType): string {
  const classes = {
    success: 'text-green-400 hover:text-green-500 focus:ring-green-500',
    error: 'text-red-400 hover:text-red-500 focus:ring-red-500',
    warning: 'text-yellow-400 hover:text-yellow-500 focus:ring-yellow-500',
    info: 'text-blue-400 hover:text-blue-500 focus:ring-blue-500'
  }
  return classes[type]
}
</script>
