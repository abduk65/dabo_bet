import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

type Permission = string
type Role = 'owner' | 'manager' | 'supervisor' | 'employee'

// Role hierarchy (higher number = more permissions)
const ROLE_HIERARCHY: Record<Role, number> = {
  owner: 4,
  manager: 3,
  supervisor: 2,
  employee: 1
}

export function usePermissions() {
  const authStore = useAuthStore()

  const userRole = computed<Role | null>(() => {
    if (!authStore.user) return null
    return authStore.user.role as Role
  })

  const roleLevel = computed(() => {
    if (!userRole.value) return 0
    return ROLE_HIERARCHY[userRole.value] || 0
  })

  // Check if user has a specific role
  function hasRole(role: Role): boolean {
    return userRole.value === role
  }

  // Check if user has at least the specified role level
  function hasMinRole(role: Role): boolean {
    const requiredLevel = ROLE_HIERARCHY[role]
    return roleLevel.value >= requiredLevel
  }

  // Check if user can perform a specific action
  function can(permission: Permission): boolean {
    if (!authStore.user) return false

    // Owner can do everything
    if (userRole.value === 'owner') return true

    // Define permission mappings
    const permissions: Record<Permission, Role[]> = {
      // Inventory
      'inventory.create': ['owner', 'manager'],
      'inventory.update': ['owner', 'manager'],
      'inventory.delete': ['owner'],
      'inventory.approve-adjustment': ['owner', 'manager'],

      // Purchase Orders
      'purchase-orders.create': ['owner', 'manager', 'supervisor'],
      'purchase-orders.approve': ['owner', 'manager'],
      'purchase-orders.receive': ['owner', 'manager', 'supervisor'],

      // Production
      'production.create-order': ['owner', 'manager'],
      'production.start': ['owner', 'manager', 'supervisor'],
      'production.record-consumption': ['owner', 'manager', 'supervisor'],
      'production.record-output': ['owner', 'manager', 'supervisor'],
      'production.complete': ['owner', 'manager'],

      // Sales
      'sales.create': ['owner', 'manager', 'supervisor', 'employee'],
      'sales.approve': ['owner', 'manager'],
      'sales.void': ['owner', 'manager'],

      // Payments
      'payments.create': ['owner', 'manager', 'supervisor', 'employee'],
      'payments.confirm': ['owner', 'manager'],
      'payments.void': ['owner', 'manager'],

      // Finance
      'finance.create-entry': ['owner', 'manager'],
      'finance.post-entry': ['owner', 'manager'],
      'finance.reverse-entry': ['owner'],
      'finance.view-reports': ['owner', 'manager'],

      // Users
      'users.create': ['owner'],
      'users.update': ['owner'],
      'users.delete': ['owner'],

      // Settings
      'settings.update': ['owner', 'manager']
    }

    const allowedRoles = permissions[permission]
    if (!allowedRoles) return false

    return userRole.value ? allowedRoles.includes(userRole.value) : false
  }

  // Computed properties for common role checks
  const isOwner = computed(() => hasRole('owner'))
  const isManager = computed(() => hasMinRole('manager'))
  const isSupervisor = computed(() => hasMinRole('supervisor'))
  const isEmployee = computed(() => hasMinRole('employee'))

  return {
    userRole,
    roleLevel,
    hasRole,
    hasMinRole,
    can,
    isOwner,
    isManager,
    isSupervisor,
    isEmployee
  }
}
