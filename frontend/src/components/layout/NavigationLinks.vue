<template>
  <ul role="list" class="flex flex-1 flex-col gap-y-7">
    <li>
      <ul role="list" class="-mx-2 space-y-1">
        <li v-for="item in filteredNavigation" :key="item.name">
          <RouterLink
            :to="item.to"
            :class="[
              isActive(item.to)
                ? 'bg-gray-50 text-primary-600'
                : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50',
              'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
            ]"
          >
            <component
              :is="item.icon"
              :class="[
                isActive(item.to) ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-600',
                'h-6 w-6 shrink-0'
              ]"
              aria-hidden="true"
            />
            {{ item.name }}
          </RouterLink>
        </li>
      </ul>
    </li>

    <!-- User info at bottom -->
    <li class="mt-auto">
      <div class="flex items-center gap-x-4 px-2 py-3 text-sm font-semibold leading-6 text-gray-900 border-t border-gray-200">
        <div class="flex-1">
          <p class="truncate">{{ authStore.user?.name }}</p>
          <p class="text-xs text-gray-500 capitalize">{{ authStore.user?.role }}</p>
        </div>
      </div>
    </li>
  </ul>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  HomeIcon,
  CubeIcon,
  ShoppingCartIcon,
  CogIcon,
  ChartBarIcon,
  UserGroupIcon,
  TruckIcon
} from '@heroicons/vue/24/outline'

const route = useRoute()
const authStore = useAuthStore()

interface NavItem {
  name: string
  to: string
  icon: any
  requiredRole?: 'owner' | 'manager' | 'supervisor' | 'employee'
}

const navigation: NavItem[] = [
  { name: 'Dashboard', to: '/', icon: HomeIcon },
  { name: 'Inventory', to: '/inventory/items', icon: CubeIcon },
  { name: 'Purchase Orders', to: '/inventory/purchase-orders', icon: ShoppingCartIcon, requiredRole: 'manager' },
  { name: 'Production', to: '/production/orders', icon: CogIcon },
  { name: 'Sales', to: '/sales/pos', icon: ChartBarIcon },
  { name: 'Customers', to: '/sales/customers', icon: UserGroupIcon },
  { name: 'Dispatches', to: '/sales/dispatches', icon: TruckIcon },
  { name: 'Finance', to: '/finance/accounts', icon: ChartBarIcon, requiredRole: 'manager' },
]

const filteredNavigation = computed(() => {
  return navigation.filter(item => {
    if (!item.requiredRole) return true
    return authStore.canAccess(item.requiredRole)
  })
})

function isActive(path: string): boolean {
  return route.path.startsWith(path) && path !== '/' || route.path === path
}
</script>
