import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router'
import Style from '@/views/base_view/StyleView.vue'
import Home from '@/views/base_view/HomeView.vue'
import { useAuthStore } from '@/stores/authentication'
import { useMainStore } from '@/stores/main'
import BaseView from '@/views/base_view/BaseView.vue'

const routes = [
  {
    meta: {
      title: 'Login'
    },
    path: '/mukera',
    name: 'Mukera',
    component: () => import('@/views/base_view/Mukera.vue')
  },
  {
    meta: {
      title: 'Login',
      allowedRoles: ['worker', 'store_keeper', 'admin']
    }
    ,
    path: '/login',
    name: 'login',
    component: () => import('@/views/base_view/LoginView.vue')
  },
  {
    meta: {
      title: 'Select style',
      requiresAuth: true
      ,
      allowedRoles: ['worker', 'store_keeper', 'admin']
    }
    ,
    path: '/',
    component: BaseView,
    children: [
      {
        meta: {
          title: 'Dashboard'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'dashboard',
        name: 'dashboard',
        component: Home
      },
      {
        meta: {
          title: 'Branches'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'branches',
        name: 'branches',
        component: () => import('@/views/branch/BranchView.vue')
      }, {
        meta: {
          title: 'Add Branch'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addBranch',
        name: 'AddBranch',
        component: () => import('@/views/branch/AddBranch.vue'),
        props: true
      }, {
        meta: {
          title: 'Edit Branch'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editBranch/:id',
        name: 'EditBranch',
        component: () => import('@/views/branch/EditBranch.vue'),
        props: true
      },
      {
        meta: {
          title: 'Daily Sales'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'dailySales',
        name: 'DailySales',
        component: () => import('@/views/daily_sales/DailySalesView.vue')
      },
      {
        meta: {
          title: 'Add Daily Sales'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'addDailySales',
        name: 'AddDailySales',
        component: () => import('@/views/daily_sales/AddDailySales.vue')
      },

      {
        meta: {
          title: 'Brands'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/brands',
        name: 'Brands',
        component: () => import('@/views/brand/BrandView.vue')
      },
      {
        meta: {
          title: 'Add Brand'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addBrand',
        name: 'AddBrand',
        component: () => import('@/views/brand/AddBrand.vue'),
      },
      {
        meta: {
          title: 'Edit Brand', allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/editBrand/:id',
        name: 'EditBrand', component: () => import('@/views/brand/EditBrand.vue'),
        props: true
      },
      {
        meta: {
          title: 'Commission Recipients'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/commissionRecipients',
        name: 'CommissionRecipients',
        component: () => import('@/views/commission_recipient/CommissionRecipientView.vue')
      },
      {
        meta: {
          title: 'Commission Recipients'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addCommissionRecipient',
        name: 'AddCommissionRecipient',
        component: () => import('@/views/commission_recipient/AddCommissionRecipient.vue')
      },
      {
        meta: {
          title: 'Edit Commission Recipients'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editCommissionRecipient/:id',
        name: 'EditCommissionRecipient',
        component: () => import('@/views/commission_recipient/EditCommissionRecipient.vue'),
        props: true
      },
      {
        meta: {
          title: 'Commission',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/commissions',
        name: 'Commission',
        component: () => import('@/views/commission/CommissionView.vue')
      },
      {
        meta: {
          title: 'Add Commission'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addCommission',
        name: 'AddCommission',
        component: () => import('@/views/commission/AddCommission.vue')
      },
      {
        meta: {
          title: 'Edit Commission'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editCommission/:id',
        name: 'EditCommission',
        component: () => import('@/views/commission/EditCommission.vue'),
        props: true
      },
      {
        meta: {
          title: 'Units'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/unit',
        name: 'Unit',
        component: () => import('@/views/unit/UnitView.vue')
      },
      {
        meta: {
          title: 'Add Units',

          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/addUnit',
        name: 'AddUnit',
        component: () => import('@/views/unit/AddUnit.vue')
      },
      {
        meta: {
          title: 'Edit Units',

          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editUnit/:id',
        name: 'EditUnit',
        component: () => import('@/views/unit/EditUnit.vue'),
        props: true,
      },
      // END UNIT

      {
        meta: {
          title: 'Standard Batch Variety',

          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/standardBatchVariety',
        name: 'StandardBatchVariety',
        component: () => import('@/views/standard_batch_variety/StandardBatchVarietyView.vue')
      },
      {
        meta: {
          title: 'Add Standard Batch Variety'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addStandardBatchVariety',
        name: 'AddStandardBatchVariety',
        component: () => import('@/views/standard_batch_variety/AddStandardBatchVariety.vue')
      },
      {
        meta: {
          title: 'ProductType'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/productType',
        name: 'ProductType',
        component: () => import('@/views/product_type/ProductTypeView.vue')
      },
      {
        meta: {
          title: 'Products'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/products',
        name: 'Products',
        component: () => import('@/views/product/ProductView.vue')
      },
      {
        meta: {
          title: 'Add Product'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addProduct',
        name: 'AddProduct',
        component: () => import('@/views/product/AddProduct.vue')
      },
      {
        meta: {
          title: 'Edit Product',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/editProduct/:id',
        name: 'EditProduct',
        component: () => import('@/views/product/EditProduct.vue'),
        props: true
      },
      {
        meta: {
          title: 'Recipes'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/recipes',
        name: 'Recipes',
        component: () => import('@/views/recipe/RecipeView.vue')
      },
      {
        meta: {
          title: 'Create Recipe'
        },
        path: '/add-recipe',
        name: 'CreateRecipe',
        component: () => import('@/views/recipe/CreateRecipe.vue')
      },
      //inventory item
      {
        meta: {
          title: 'Inventory Item'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/inventoryItem',
        name: 'InventoryItem',
        component: () => import('@/views/inventory_item/InventoryItemView.vue')
      },
      {
        meta: {
          title: 'Add Inventory Item',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addInventoryItem',
        name: 'AddInventoryItem',
        component: () => import('@/views/inventory_item/AddInventoryItem.vue')
      },
      {
        meta: {
          title: 'Edit Inventory Item',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editInventoryItem/:id',
        name: 'EditInventoryItem',
        component: () => import('@/views/inventory_item/EditInventoryItem.vue'),
        props: true
      },
      {
        meta: {
          title: 'Daily Inventory Out',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/dailyInventoryOut',
        name: 'DailyInventoryOut',
        component: () => import('@/views/daily_inventory_out/DailyInventoryOutView.vue')
      },
      {
        meta: {
          title: ' Add Daily Inventory Out',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/addDailyInventoryOut',
        name: 'AddDailyInventoryOut',
        component: () => import('@/views/daily_inventory_out/AddDailyInventoryOut.vue')
      },
      {
        meta: {
          title: ' Edit Daily Inventory Out',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/editDailyInventoryOut/:id',
        name: 'EditDailyInventoryOut',
        component: () => import('@/views/daily_inventory_out/EditDailyInventoryOut.vue'),
        props: true
      },
      //product type
      {
        meta: {
          title: 'Add Product Type'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addProductType',
        name: 'AddProductType',
        component: () => import('@/views/product_type/AddProductType.vue')
      },
      {
        meta: {
          title: 'Edit Product Type',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: '/editProductType/:id',
        name: 'EditProductType',
        component: () => import('@/views/product_type/EditProductType.vue'),
        props: true
      },
      {
        meta: {
          title: 'Profit Report'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/profitReport',
        name: 'ProfitReport',
        component: () => import('@/views/ProfitReportView.vue')
      },

      // cash collected
      {
        meta: {
          title: 'Cash Collected'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/cashCollected',
        name: 'CashCollected',
        component: () => import('@/views/cash_collected/CashCollectedView.vue')
      },
      {
        meta: {
          title: 'Add Cash Collected'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addCashCollected',
        name: 'AddCashCollected',
        component: () => import('@/views/cash_collected/AddCashCollected.vue')
      },
      {
        meta: {
          title: 'Edit Cash Collected'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/editCashCollected/:id',
        name: 'EditCashCollected',
        component: () => import('@/views/cash_collected/EditCashCollected.vue'),
        props: true
      },

      {
        meta: {
          title: 'Work Orders'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/workOrders',
        name: 'WorkOrders',
        component: () => import('@/views/work_order/WorkOrderView.vue')
      },
      {
        meta: {
          title: 'Add Work Order'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/addWorkOrder',
        name: 'AddWorkOrder',
        component: () => import('@/views/work_order/AddWorkOrder.vue')
      },


      {
        meta: {
          title: 'Forms'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'forms',
        name: 'forms',
        component: () => import('@/views/base_view/FormsView.vue')
      },
      {
        meta: {
          title: 'Profile'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: 'profile',
        name: 'profile',
        component: () => import('@/views/base_view/ProfileView.vue')
      },
      {
        meta: {
          title: 'Ui'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/ui',
        name: 'ui',
        component: () => import('@/views/base_view/UiView.vue')
      }, {
        meta: {
          title: 'Style'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/style',
        name: 'style',
        component: () => import('@/views/base_view/UiView.vue')
      },
      {
        meta: {
          title: 'Responsive layout'
          ,
          allowedRoles: ['worker', 'store_keeper', 'admin']
        }
        ,
        path: '/responsive',
        name: 'responsive',
        component: () => import('@/views/base_view/ResponsiveView.vue')
      },
      {
        path: '/addExpense',
        name: 'AddExpense',
        component: () => import('@/views/expense/AddExpense.vue')
      },
      {
        path: '/editExpense/:id',
        name: 'EditExpense',
        component: () => import('@/views/expense/EditExpense.vue'),
        props: true
      },
      {
        path: '/expenses',
        name: 'Expenses',
        component: () => import('@/views/expense/ExpenseView.vue')
      },
      {
        meta: {
          title: 'Inventory Adjustments',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: 'inventoryAdjustments',
        name: 'InventoryAdjustment',
        component: () => import('@/views/inventory_adjustment/InventoryAdjustmentView.vue')
      },
      {
        meta: {
          title: 'Add Inventory Adjustment',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: 'addInventoryAdjustment',
        name: 'AddInventoryAdjustment',
        component: () => import('@/views/inventory_adjustment/AddInventoryAdjustment.vue')
      },
      {
        meta: {
          title: 'Edit Inventory Adjustment',
          allowedRoles: ['worker', 'store_keeper', 'admin']
        },
        path: 'editInventoryAdjustment/:id',
        name: 'EditInventoryAdjustment',
        component: () => import('@/views/inventory_adjustment/EditInventoryAdjustment.vue'),
        props: true
      },
    ]
  },


  {
    meta: {
      title: 'Error'
      ,
      allowedRoles: ['worker', 'store_keeper', 'admin']
    }
    ,
    path: '/error',
    name: 'error',
    component: () => import('@/views/base_view/ErrorView.vue')
  }

]

// const mainStore = useMainStore()

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 }
  }
})

function hasPermission(role, action, model) {

}

router.beforeEach((to, from, next) => {
  const store = useAuthStore()
  const token = localStorage.getItem('authToken')
  const user = localStorage.getItem('user')
  const role = user ? JSON.parse(user).role : null;
  const allowedRoles = to.meta.allowedRoles

  if (to.name === 'login') {
    // Allow access to login route
    next()
  } else if (!token) {
    // If no token in localStorage, redirect to login
    next({ name: 'login' })
  } else {
    if (!allowedRoles.includes(role)) {
      next('dashboard')
    }
    next()
  }
})

router.onError((err) => console.log(err))
export default router
