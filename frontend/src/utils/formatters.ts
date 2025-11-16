const LOCALE = 'en-ET'

/**
 * Format date to localized string
 */
export function formatDate(date: string | Date | null | undefined, format: 'short' | 'long' | 'full' = 'short'): string {
  if (!date) return '-'

  const dateObj = typeof date === 'string' ? new Date(date) : date

  if (isNaN(dateObj.getTime())) return '-'

  switch (format) {
    case 'short':
      return dateObj.toLocaleDateString(LOCALE, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })

    case 'long':
      return dateObj.toLocaleDateString(LOCALE, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })

    case 'full':
      return dateObj.toLocaleDateString(LOCALE, {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })

    default:
      return dateObj.toLocaleDateString(LOCALE)
  }
}

/**
 * Format datetime to localized string
 */
export function formatDateTime(dateTime: string | Date | null | undefined): string {
  if (!dateTime) return '-'

  const dateObj = typeof dateTime === 'string' ? new Date(dateTime) : dateTime

  if (isNaN(dateObj.getTime())) return '-'

  return dateObj.toLocaleString(LOCALE, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

/**
 * Format time to localized string
 */
export function formatTime(time: string | Date | null | undefined): string {
  if (!time) return '-'

  const dateObj = typeof time === 'string' ? new Date(time) : time

  if (isNaN(dateObj.getTime())) return '-'

  return dateObj.toLocaleTimeString(LOCALE, {
    hour: '2-digit',
    minute: '2-digit'
  })
}

/**
 * Format number with thousands separator
 */
export function formatNumber(value: number | string | null | undefined, decimals = 0): string {
  if (value === null || value === undefined || value === '') return '0'

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  if (isNaN(numValue)) return '0'

  return new Intl.NumberFormat(LOCALE, {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  }).format(numValue)
}

/**
 * Format currency (ETB)
 */
export function formatCurrency(value: number | string | null | undefined, compact = false): string {
  if (value === null || value === undefined || value === '') return 'ETB 0.00'

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  if (isNaN(numValue)) return 'ETB 0.00'

  return new Intl.NumberFormat(LOCALE, {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: compact ? 0 : 2,
    maximumFractionDigits: compact ? 0 : 2
  }).format(numValue)
}

/**
 * Format percentage
 */
export function formatPercentage(value: number | string | null | undefined, decimals = 1): string {
  if (value === null || value === undefined || value === '') return '0%'

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  if (isNaN(numValue)) return '0%'

  return `${formatNumber(numValue, decimals)}%`
}

/**
 * Format file size in bytes to human-readable format
 */
export function formatFileSize(bytes: number): string {
  if (bytes === 0) return '0 Bytes'

  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))

  return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i]
}

/**
 * Format phone number (Ethiopian format)
 */
export function formatPhoneNumber(phone: string | null | undefined): string {
  if (!phone) return '-'

  // Remove all non-digits
  const cleaned = phone.replace(/\D/g, '')

  // Format as Ethiopian phone number (e.g., +251 91 234 5678)
  if (cleaned.length === 12 && cleaned.startsWith('251')) {
    return `+${cleaned.slice(0, 3)} ${cleaned.slice(3, 5)} ${cleaned.slice(5, 8)} ${cleaned.slice(8)}`
  }

  if (cleaned.length === 10 && cleaned.startsWith('0')) {
    return `${cleaned.slice(0, 2)} ${cleaned.slice(2, 5)} ${cleaned.slice(5, 9)}`
  }

  if (cleaned.length === 9) {
    return `0${cleaned.slice(0, 2)} ${cleaned.slice(2, 5)} ${cleaned.slice(5)}`
  }

  return phone
}

/**
 * Truncate text with ellipsis
 */
export function truncate(text: string | null | undefined, length: number): string {
  if (!text) return ''

  if (text.length <= length) return text

  return text.substring(0, length) + '...'
}

/**
 * Format relative time (e.g., "2 hours ago")
 */
export function formatRelativeTime(date: string | Date | null | undefined): string {
  if (!date) return '-'

  const dateObj = typeof date === 'string' ? new Date(date) : date

  if (isNaN(dateObj.getTime())) return '-'

  const now = new Date()
  const diffMs = now.getTime() - dateObj.getTime()
  const diffSec = Math.floor(diffMs / 1000)
  const diffMin = Math.floor(diffSec / 60)
  const diffHour = Math.floor(diffMin / 60)
  const diffDay = Math.floor(diffHour / 24)

  if (diffSec < 60) return 'Just now'
  if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`
  if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`
  if (diffDay < 7) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`
  if (diffDay < 30) return `${Math.floor(diffDay / 7)} week${Math.floor(diffDay / 7) > 1 ? 's' : ''} ago`
  if (diffDay < 365) return `${Math.floor(diffDay / 30)} month${Math.floor(diffDay / 30) > 1 ? 's' : ''} ago`

  return formatDate(dateObj, 'short')
}

/**
 * Capitalize first letter
 */
export function capitalize(text: string | null | undefined): string {
  if (!text) return ''
  return text.charAt(0).toUpperCase() + text.slice(1)
}

/**
 * Convert snake_case to Title Case
 */
export function snakeToTitle(text: string | null | undefined): string {
  if (!text) return ''
  return text
    .split('_')
    .map(word => capitalize(word))
    .join(' ')
}

/**
 * Format boolean as Yes/No
 */
export function formatBoolean(value: boolean | null | undefined): string {
  if (value === null || value === undefined) return '-'
  return value ? 'Yes' : 'No'
}
