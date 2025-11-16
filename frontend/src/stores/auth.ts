import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/auth.service'
import type { User, LoginCredentials } from '@/types'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(authService.getUser())
  const token = ref<string | null>(authService.getToken())
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)
  const userRole = computed(() => user.value?.role)

  const isOwner = computed(() => user.value?.role === 'owner')
  const isManager = computed(() => ['owner', 'manager'].includes(user.value?.role || ''))
  const isSupervisor = computed(() => ['owner', 'manager', 'supervisor'].includes(user.value?.role || ''))

  async function login(credentials: LoginCredentials) {
    loading.value = true
    error.value = null

    try {
      const response = await authService.login(credentials)

      user.value = response.data.user
      token.value = response.data.token

      authService.saveToken(response.data.token)
      authService.saveUser(response.data.user)

      return response
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true

    try {
      await authService.logout()
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      user.value = null
      token.value = null
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return

    try {
      const fetchedUser = await authService.getCurrentUser()
      user.value = fetchedUser
      authService.saveUser(fetchedUser)
    } catch (err) {
      console.error('Fetch user error:', err)
      user.value = null
      token.value = null
      authService.logout()
    }
  }

  function hasRole(role: string | string[]): boolean {
    if (!user.value) return false
    const roles = Array.isArray(role) ? role : [role]
    return roles.includes(user.value.role)
  }

  function canAccess(requiredRole: 'owner' | 'manager' | 'supervisor' | 'employee'): boolean {
    if (!user.value) return false

    const roleHierarchy = {
      owner: 4,
      manager: 3,
      supervisor: 2,
      employee: 1
    }

    return roleHierarchy[user.value.role] >= roleHierarchy[requiredRole]
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    userRole,
    isOwner,
    isManager,
    isSupervisor,
    login,
    logout,
    fetchUser,
    hasRole,
    canAccess
  }
})
