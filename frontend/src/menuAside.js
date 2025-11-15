import {
  mdiAccountCircle,
  mdiMonitor,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact
} from '@mdi/js'

export default [
  {
    to: '/dashboard',
    icon: mdiMonitor,
    label: 'dashboard',
  },
  {
    to: '/dailySales',
    label: 'Daily Sales',
    icon: mdiTable
  },
  {
    to: '/inventoryItem',
    label: 'Inventory Items',
    icon: mdiTable
  },
  {
    to: '/dailyInventoryOut',
    label: 'Daily Inventory Out',
    icon: mdiTable
  },
  {
    to: '/cashCollected',
    label: 'Cash Collected',
    icon: mdiTable
  },
  {
    to: '/workOrders',
    label: 'Work Orders',
    icon: mdiTable
  },

  {
    to: '/expenses',
    icon: mdiViewList,
    label: 'View Expenses'
  },
  {
    to: '/profitReport',
    label: 'Profit Report',
    icon: mdiTable
  },
  {
    label: 'EXPAND',
    icon: mdiViewList,
    menu: [
      {
        to: '/branches',
        label: 'Branches',
        icon: mdiTable
      },
      {
        to: '/brands',
        label: 'brands',
        icon: mdiTable
      },
      {
        to: '/commissionRecipients',
        label: 'Commission Recipient',
        icon: mdiTable
      },
      {
        to: '/commissions',
        label: 'Commission',
        icon: mdiTable
      },
      {
        to: '/unit',
        label: 'Unit',
        icon: mdiTable
      },
      {
        to: '/productType',
        label: 'Product Type',
        icon: mdiTable
      },
      {
        to: '/recipes',
        label: 'Recipes',
        icon: mdiTable
      },
      {
        to: '/products',
        label: 'Products',
        icon: mdiTable
      },
      {
        to: '/inventoryAdjustments',
        label: 'Inventory Adjustments',
        icon: mdiTable
      },
      {
        to: '/standardBatchVariety',
        label: 'Standard Batch Variety',
        icon: mdiTable
      },
    ]
  },
  {
    to: '/forms',
    label: 'Forms',
    icon: mdiSquareEditOutline
  },
  {
    to: '/ui',
    label: 'UI',
    icon: mdiTelevisionGuide
  },
  {
    to: '/responsive',
    label: 'Responsive',
    icon: mdiResponsive
  },
  {
    to: '/style',
    label: 'Styles',
    icon: mdiPalette
  },
  {
    to: '/profile',
    label: 'Profile',
    icon: mdiAccountCircle
  },
]
