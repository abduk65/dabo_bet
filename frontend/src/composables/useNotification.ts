import { ref } from 'vue'

export type NotificationType = 'success' | 'error' | 'warning' | 'info'

export interface Notification {
  id: string
  type: NotificationType
  title: string
  message?: string
  duration?: number
}

const notifications = ref<Notification[]>([])

export function useNotification() {
  function notify(type: NotificationType, title: string, message?: string, duration = 5000) {
    const id = `notification-${Date.now()}-${Math.random()}`

    const notification: Notification = {
      id,
      type,
      title,
      message,
      duration
    }

    notifications.value.push(notification)

    // Auto-remove after duration
    if (duration > 0) {
      setTimeout(() => {
        remove(id)
      }, duration)
    }

    return id
  }

  function remove(id: string) {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }

  function clear() {
    notifications.value = []
  }

  // Convenience methods
  function success(title: string, message?: string, duration?: number) {
    return notify('success', title, message, duration)
  }

  function error(title: string, message?: string, duration?: number) {
    return notify('error', title, message, duration)
  }

  function warning(title: string, message?: string, duration?: number) {
    return notify('warning', title, message, duration)
  }

  function info(title: string, message?: string, duration?: number) {
    return notify('info', title, message, duration)
  }

  return {
    notifications,
    notify,
    remove,
    clear,
    success,
    error,
    warning,
    info
  }
}
