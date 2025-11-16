import axios, { type AxiosError, type AxiosRequestConfig } from 'axios'
import { useAuthStore } from '@/stores/auth'
import router from '@/router'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 30000, // 30 seconds for Ethiopian network conditions
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  },
  withCredentials: true // For CSRF token
})

// Track request queue for offline handling
const offlineQueue: AxiosRequestConfig[] = []
let isOnline = navigator.onLine

// Monitor online/offline status
window.addEventListener('online', () => {
  isOnline = true
  processOfflineQueue()
})

window.addEventListener('offline', () => {
  isOnline = false
})

// REQUEST INTERCEPTOR - Add auth token, handle offline
apiClient.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore()

    // Add auth token
    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`
    }

    // Add language preference
    const locale = localStorage.getItem('locale') || 'en'
    config.headers['Accept-Language'] = locale

    // Add branch context if available
    if (authStore.user?.branch_id) {
      config.headers['X-Branch-ID'] = authStore.user.branch_id.toString()
    }

    // Check if offline (for non-GET requests)
    if (!isOnline && config.method !== 'get') {
      queueRequest(config)
      return Promise.reject({
        isOffline: true,
        message: 'No internet connection - request queued for later',
        config
      })
    }

    return config
  },
  (error) => Promise.reject(error)
)

// RESPONSE INTERCEPTOR - Handle errors globally
apiClient.interceptors.response.use(
  (response) => {
    // Success - return response
    return response
  },
  async (error: AxiosError) => {
    // Handle offline queue rejection
    if ((error as any).isOffline) {
      return Promise.reject(error)
    }

    if (!error.response) {
      // Network error
      console.error('Network error:', error.message)
      return Promise.reject({
        message: 'Network error - please check your internet connection',
        isNetworkError: true
      })
    }

    const { status, data } = error.response

    switch (status) {
      case 401:
        // Unauthorized - logout and redirect to login
        const authStore = useAuthStore()
        authStore.logout()
        router.push('/login')
        return Promise.reject({
          message: 'Session expired - please login again',
          isAuthError: true
        })

      case 403:
        // Forbidden - permission issue
        return Promise.reject({
          message: 'You do not have permission for this action',
          isForbidden: true
        })

      case 422:
        // Validation errors - pass through for component handling
        return Promise.reject({
          message: 'Validation failed',
          errors: (data as any).errors || {},
          isValidationError: true
        })

      case 429:
        // Rate limit
        return Promise.reject({
          message: 'Too many requests - please wait a moment',
          isRateLimit: true
        })

      case 500:
      case 502:
      case 503:
      case 504:
        // Server errors
        console.error('Server error:', status, data)
        return Promise.reject({
          message: 'Server error - please try again later',
          isServerError: true
        })

      default:
        return Promise.reject({
          message: (data as any)?.message || 'An error occurred',
          status
        })
    }
  }
)

// Queue management for offline requests
function queueRequest(config: AxiosRequestConfig) {
  offlineQueue.push(config)
  localStorage.setItem('offlineQueue', JSON.stringify(offlineQueue))
  console.log('Request queued for offline sync:', config.url)
}

async function processOfflineQueue() {
  const queued = [...offlineQueue]
  offlineQueue.length = 0 // Clear queue

  console.log(`Processing ${queued.length} queued requests`)

  for (const config of queued) {
    try {
      await apiClient.request(config)
      console.log('Successfully synced:', config.url)
    } catch (error) {
      console.error('Failed to sync:', config.url, error)
      // Re-queue if still failing
      offlineQueue.push(config)
    }
  }

  localStorage.setItem('offlineQueue', JSON.stringify(offlineQueue))
}

// Load queued requests from localStorage on app start
const savedQueue = localStorage.getItem('offlineQueue')
if (savedQueue) {
  try {
    const parsed = JSON.parse(savedQueue)
    offlineQueue.push(...parsed)

    // Try to process immediately if online
    if (isOnline && offlineQueue.length > 0) {
      setTimeout(processOfflineQueue, 1000)
    }
  } catch (e) {
    console.error('Failed to parse offline queue:', e)
    localStorage.removeItem('offlineQueue')
  }
}

export default apiClient
export { processOfflineQueue, offlineQueue }
