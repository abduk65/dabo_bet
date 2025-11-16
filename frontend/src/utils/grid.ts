import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'
import { formatCurrency, formatDate, formatDateTime, formatNumber, formatPhoneNumber } from './formatters'
import StatusBadge from '@/components/common/StatusBadge.vue'

/**
 * Column helper functions for common data types
 */

export function createTextColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    cellClass?: string
    headerClass?: string
    sortable?: boolean
    filterable?: boolean
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    enableSorting: options?.sortable ?? true,
    enableColumnFilter: options?.filterable ?? true,
    meta: {
      cellClass: options?.cellClass,
      headerClass: options?.headerClass
    }
  }
}

export function createNumberColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    format?: boolean
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as number
      return options?.format !== false ? formatNumber(value) : value
    },
    meta: {
      cellClass: `text-right ${options?.cellClass || ''}`,
      headerClass: `text-right ${options?.headerClass || ''}`
    }
  }
}

export function createCurrencyColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    compact?: boolean
    cellClass?: string
    headerClass?: string
    colorize?: boolean // Color negative values red
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as number
      const formatted = formatCurrency(value, options?.compact)

      if (options?.colorize && value < 0) {
        return h('span', { class: 'text-red-600 font-medium' }, formatted)
      }

      return formatted
    },
    meta: {
      cellClass: `text-right font-medium ${options?.cellClass || ''}`,
      headerClass: `text-right ${options?.headerClass || ''}`
    }
  }
}

export function createDateColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    showTime?: boolean
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as string | Date
      if (!value) return '-'

      const date = typeof value === 'string' ? new Date(value) : value
      return options?.showTime ? formatDateTime(date) : formatDate(date)
    },
    meta: {
      cellClass: options?.cellClass,
      headerClass: options?.headerClass
    }
  }
}

export function createPhoneColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as string
      return formatPhoneNumber(value)
    },
    meta: {
      cellClass: `font-mono ${options?.cellClass || ''}`,
      headerClass: options?.headerClass
    }
  }
}

export function createStatusColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    type?: 'status' | 'paymentMethod' | 'accountType'
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as string
      const props: Record<string, any> = {
        label: value.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
      }

      // Set the appropriate prop based on type
      if (options?.type === 'paymentMethod') {
        props.paymentMethod = value
      } else if (options?.type === 'accountType') {
        props.accountType = value
      } else {
        props.status = value
      }

      return h(StatusBadge, props)
    },
    enableSorting: true,
    enableColumnFilter: true,
    meta: {
      cellClass: options?.cellClass,
      headerClass: options?.headerClass
    }
  }
}

export function createBooleanColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    labels?: { true: string; false: string }
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as boolean
      const labels = options?.labels || { true: 'Yes', false: 'No' }
      const label = value ? labels.true : labels.false

      return h('span', {
        class: value ? 'text-green-700 font-medium' : 'text-gray-500'
      }, label)
    },
    meta: {
      cellClass: options?.cellClass,
      headerClass: options?.headerClass
    }
  }
}

export function createImageColumn<TData>(
  accessorKey: string,
  header: string,
  options?: {
    size?: 'sm' | 'md' | 'lg'
    fallback?: string
    cellClass?: string
    headerClass?: string
  }
): ColumnDef<TData> {
  const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-10 w-10',
    lg: 'h-12 w-12'
  }

  return {
    accessorKey,
    header,
    cell: ({ getValue }) => {
      const value = getValue() as string | null
      const size = options?.size || 'sm'

      if (!value) {
        return h('div', {
          class: `${sizeClasses[size]} rounded bg-gray-200 flex items-center justify-center`
        }, [
          h('span', { class: 'text-xs text-gray-500' }, options?.fallback || '?')
        ])
      }

      return h('img', {
        src: value,
        alt: header,
        class: `${sizeClasses[size]} rounded object-cover`
      })
    },
    enableSorting: false,
    enableColumnFilter: false,
    meta: {
      cellClass: options?.cellClass,
      headerClass: options?.headerClass
    }
  }
}

/**
 * Export data to CSV
 */
export function exportToCSV<TData>(
  data: TData[],
  columns: ColumnDef<TData>[],
  filename: string = 'export.csv'
) {
  if (data.length === 0) {
    console.warn('No data to export')
    return
  }

  // Get column headers
  const headers = columns
    .filter(col => 'accessorKey' in col && col.accessorKey)
    .map(col => {
      if (typeof col.header === 'string') {
        return col.header
      }
      return (col as any).accessorKey
    })

  // Get column accessor keys
  const accessorKeys = columns
    .filter(col => 'accessorKey' in col && col.accessorKey)
    .map(col => (col as any).accessorKey)

  // Build CSV rows
  const csvRows = [
    headers.join(','), // Header row
    ...data.map(row => {
      return accessorKeys.map(key => {
        const value = (row as any)[key]

        // Handle null/undefined
        if (value === null || value === undefined) {
          return ''
        }

        // Handle objects/arrays
        if (typeof value === 'object') {
          return `"${JSON.stringify(value).replace(/"/g, '""')}"`
        }

        // Handle strings with commas or quotes
        const stringValue = String(value)
        if (stringValue.includes(',') || stringValue.includes('"') || stringValue.includes('\n')) {
          return `"${stringValue.replace(/"/g, '""')}"`
        }

        return stringValue
      }).join(',')
    })
  ]

  // Create blob and download
  const csvContent = csvRows.join('\n')
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')

  if (link.download !== undefined) {
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', filename)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
  }
}

/**
 * Export data to Excel (using HTML table method)
 */
export function exportToExcel<TData>(
  data: TData[],
  columns: ColumnDef<TData>[],
  filename: string = 'export.xls'
) {
  if (data.length === 0) {
    console.warn('No data to export')
    return
  }

  // Get column headers
  const headers = columns
    .filter(col => 'accessorKey' in col && col.accessorKey)
    .map(col => {
      if (typeof col.header === 'string') {
        return col.header
      }
      return (col as any).accessorKey
    })

  // Get column accessor keys
  const accessorKeys = columns
    .filter(col => 'accessorKey' in col && col.accessorKey)
    .map(col => (col as any).accessorKey)

  // Build HTML table
  let html = '<table><thead><tr>'
  headers.forEach(header => {
    html += `<th>${header}</th>`
  })
  html += '</tr></thead><tbody>'

  data.forEach(row => {
    html += '<tr>'
    accessorKeys.forEach(key => {
      const value = (row as any)[key]
      html += `<td>${value !== null && value !== undefined ? value : ''}</td>`
    })
    html += '</tr>'
  })

  html += '</tbody></table>'

  // Create blob and download
  const blob = new Blob([html], { type: 'application/vnd.ms-excel' })
  const link = document.createElement('a')

  if (link.download !== undefined) {
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', filename)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
  }
}

/**
 * Common filter functions
 */
export const fuzzyFilter = (row: any, columnId: string, value: string) => {
  const itemValue = row.getValue(columnId)
  if (itemValue === null || itemValue === undefined) return false

  const searchValue = value.toLowerCase()
  const cellValue = String(itemValue).toLowerCase()

  return cellValue.includes(searchValue)
}

export const exactFilter = (row: any, columnId: string, value: string) => {
  const itemValue = row.getValue(columnId)
  if (itemValue === null || itemValue === undefined) return false

  return String(itemValue).toLowerCase() === value.toLowerCase()
}

export const rangeFilter = (row: any, columnId: string, value: [number, number]) => {
  const itemValue = row.getValue(columnId)
  if (typeof itemValue !== 'number') return false

  const [min, max] = value
  return itemValue >= min && itemValue <= max
}

/**
 * Utility to create action column
 */
export function createActionColumn<TData>(): ColumnDef<TData> {
  return {
    id: 'actions',
    header: '',
    enableSorting: false,
    enableColumnFilter: false,
    meta: {
      cellClass: 'text-right',
      headerClass: 'w-20'
    }
  }
}

/**
 * Utility to create selection column
 */
export function createSelectionColumn<TData>(): ColumnDef<TData> {
  return {
    id: 'select',
    header: ({ table }) => {
      return h('input', {
        type: 'checkbox',
        checked: table.getIsAllRowsSelected(),
        indeterminate: table.getIsSomeRowsSelected(),
        onChange: (e: Event) => {
          table.toggleAllRowsSelected(!!(e.target as HTMLInputElement).checked)
        },
        class: 'h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500'
      })
    },
    cell: ({ row }) => {
      return h('input', {
        type: 'checkbox',
        checked: row.getIsSelected(),
        disabled: !row.getCanSelect(),
        onChange: (e: Event) => {
          row.toggleSelected(!!(e.target as HTMLInputElement).checked)
        },
        onClick: (e: Event) => {
          e.stopPropagation()
        },
        class: 'h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500'
      })
    },
    enableSorting: false,
    enableColumnFilter: false,
    meta: {
      headerClass: 'w-12',
      cellClass: 'w-12'
    }
  }
}
