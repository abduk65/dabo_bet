import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/auth/LoginView.vue'),
    meta: { requiresAuth: false, title: 'Login' }
  },
  {
    path: '/',
    component: () => import('@/layouts/MainLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: () => import('@/views/DashboardView.vue'),
        meta: { title: 'Dashboard' }
      },
      // Inventory routes
      {
        path: 'inventory',
        children: [
          {
            path: 'material-types',
            name: 'MaterialTypes',
            component: () => import('@/views/inventory/MaterialTypesView.vue'),
            meta: { title: 'Material Types' }
          },
          {
            path: 'brands',
            name: 'Brands',
            component: () => import('@/views/inventory/BrandsView.vue'),
            meta: { title: 'Brands' }
          },
          {
            path: 'items',
            name: 'InventoryItems',
            component: () => import('@/views/inventory/InventoryItemsView.vue'),
            meta: { title: 'Inventory Items' }
          },
          {
            path: 'purchase-orders',
            name: 'PurchaseOrders',
            component: () => import('@/views/inventory/PurchaseOrdersView.vue'),
            meta: { title: 'Purchase Orders', requiredRole: 'manager' }
          },
          {
            path: 'purchase-orders/:id',
            name: 'PurchaseOrderDetail',
            component: () => import('@/views/inventory/PurchaseOrderDetailView.vue'),
            meta: { title: 'Purchase Order Detail' }
          }
        ]
      },
      // Production routes
      {
        path: 'production',
        children: [
          {
            path: 'products',
            name: 'Products',
            component: () => import('@/views/production/ProductsView.vue'),
            meta: { title: 'Products' }
          },
          {
            path: 'recipes',
            name: 'Recipes',
            component: () => import('@/views/production/RecipesView.vue'),
            meta: { title: 'Recipes' }
          },
          {
            path: 'orders',
            name: 'ProductionOrders',
            component: () => import('@/views/production/ProductionOrdersView.vue'),
            meta: { title: 'Production Orders' }
          }
        ]
      },
      // Sales routes
      {
        path: 'sales',
        children: [
          {
            path: 'customers',
            name: 'Customers',
            component: () => import('@/views/sales/CustomersView.vue'),
            meta: { title: 'Customers' }
          },
          {
            path: 'pos',
            name: 'PointOfSale',
            component: () => import('@/views/sales/PointOfSaleView.vue'),
            meta: { title: 'Point of Sale' }
          },
          {
            path: 'sales-list',
            name: 'SalesList',
            component: () => import('@/views/sales/SalesListView.vue'),
            meta: { title: 'Sales List' }
          },
          {
            path: 'dispatches',
            name: 'Dispatches',
            component: () => import('@/views/sales/DispatchesView.vue'),
            meta: { title: 'Dispatches' }
          }
        ]
      },
      // Financial routes
      {
        path: 'finance',
        children: [
          {
            path: 'accounts',
            name: 'Accounts',
            component: () => import('@/views/finance/AccountsView.vue'),
            meta: { title: 'Chart of Accounts', requiredRole: 'manager' }
          },
          {
            path: 'journal-entries',
            name: 'JournalEntries',
            component: () => import('@/views/finance/JournalEntriesView.vue'),
            meta: { title: 'Journal Entries', requiredRole: 'manager' }
          },
          {
            path: 'reports',
            name: 'FinancialReports',
            component: () => import('@/views/finance/ReportsView.vue'),
            meta: { title: 'Financial Reports', requiredRole: 'owner' }
          }
        ]
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/views/NotFoundView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const requiresAuth = to.meta.requiresAuth !== false

  // Set page title
  document.title = to.meta.title ? `${to.meta.title} - Bakery ERP` : 'Bakery ERP'

  if (requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login', query: { redirect: to.fullPath } })
  } else if (to.name === 'Login' && authStore.isAuthenticated) {
    next({ name: 'Dashboard' })
  } else if (to.meta.requiredRole && !authStore.canAccess(to.meta.requiredRole as any)) {
    // User doesn't have required role
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})

export default router
