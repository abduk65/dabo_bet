import { ref, type Ref } from 'vue'
import type { AxiosResponse } from 'axios'
import { useNotification } from './useNotification'

export interface UseApiOptions {
  immediate?: boolean
  showErrorToast?: boolean
  showSuccessToast?: boolean
  successMessage?: string
  onSuccess?: (data: any) => void
  onError?: (error: any) => void
}

export function useApi<T = any>(
  apiCall: (...args: any[]) => Promise<AxiosResponse<T>>,
  options: UseApiOptions = {}
) {
  const {
    immediate = false,
    showErrorToast = true,
    showSuccessToast = false,
    successMessage,
    onSuccess,
    onError
  } = options

  const data: Ref<T | null> = ref(null)
  const loading = ref(false)
  const error: Ref<any> = ref(null)

  const { notify } = useNotification()

  async function execute(...args: any[]) {
    loading.value = true
    error.value = null

    try {
      const response = await apiCall(...args)
      data.value = response.data

      if (showSuccessToast && successMessage) {
        notify('success', successMessage)
      }

      if (onSuccess) {
        onSuccess(response.data)
      }

      return response.data
    } catch (err: any) {
      error.value = err

      if (showErrorToast) {
        const message = err.message || 'An error occurred'
        notify('error', 'Error', message)
      }

      if (onError) {
        onError(err)
      }

      throw err
    } finally {
      loading.value = false
    }
  }

  // Execute immediately if requested
  if (immediate) {
    execute()
  }

  return {
    data,
    loading,
    error,
    execute
  }
}

// Specialized composable for mutations (create, update, delete)
export function useMutation<T = any>(
  apiCall: (...args: any[]) => Promise<AxiosResponse<T>>,
  options: UseApiOptions = {}
) {
  return useApi(apiCall, {
    ...options,
    immediate: false // Mutations should never be immediate
  })
}

// Specialized composable for queries (fetch/list)
export function useQuery<T = any>(
  apiCall: (...args: any[]) => Promise<AxiosResponse<T>>,
  options: UseApiOptions = {}
) {
  return useApi(apiCall, {
    ...options,
    immediate: options.immediate ?? true, // Queries often immediate by default
    showErrorToast: options.showErrorToast ?? true
  })
}
