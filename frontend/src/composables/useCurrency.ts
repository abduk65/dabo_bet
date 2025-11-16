import { computed } from 'vue'

const LOCALE = 'en-ET'
const CURRENCY = 'ETB'

export function useCurrency() {
  const formatter = new Intl.NumberFormat(LOCALE, {
    style: 'currency',
    currency: CURRENCY,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })

  const compactFormatter = new Intl.NumberFormat(LOCALE, {
    style: 'currency',
    currency: CURRENCY,
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })

  function format(value: number | string | null | undefined, compact = false): string {
    if (value === null || value === undefined || value === '') {
      return 'ETB 0.00'
    }

    const numValue = typeof value === 'string' ? parseFloat(value) : value

    if (isNaN(numValue)) {
      return 'ETB 0.00'
    }

    return compact ? compactFormatter.format(numValue) : formatter.format(numValue)
  }

  function parse(value: string): number {
    if (!value) return 0

    // Remove currency symbol, spaces, and commas
    const cleaned = value
      .replace(/ETB/gi, '')
      .replace(/\s/g, '')
      .replace(/,/g, '')
      .trim()

    const parsed = parseFloat(cleaned)
    return isNaN(parsed) ? 0 : parsed
  }

  function formatInput(value: number | string): string {
    // Format for input fields (no currency symbol, with thousands separator)
    const numValue = typeof value === 'string' ? parseFloat(value) : value

    if (isNaN(numValue) || numValue === 0) {
      return ''
    }

    return new Intl.NumberFormat(LOCALE, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(numValue)
  }

  return {
    format,
    parse,
    formatInput
  }
}
