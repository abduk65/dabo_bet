/**
 * Validation utility functions
 * These can be used with vee-validate or standalone form validation
 */

/**
 * Check if value is required (not empty)
 */
export function required(value: any): boolean | string {
  if (value === null || value === undefined) {
    return 'This field is required'
  }

  if (typeof value === 'string' && value.trim() === '') {
    return 'This field is required'
  }

  if (Array.isArray(value) && value.length === 0) {
    return 'This field is required'
  }

  return true
}

/**
 * Validate email format
 */
export function email(value: string): boolean | string {
  if (!value) return true // Use with required() for mandatory emails

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(value) || 'Invalid email address'
}

/**
 * Validate minimum length
 */
export function minLength(min: number) {
  return (value: string): boolean | string => {
    if (!value) return true

    return value.length >= min || `Must be at least ${min} characters`
  }
}

/**
 * Validate maximum length
 */
export function maxLength(max: number) {
  return (value: string): boolean | string => {
    if (!value) return true

    return value.length <= max || `Must be at most ${max} characters`
  }
}

/**
 * Validate minimum value
 */
export function minValue(min: number) {
  return (value: number | string): boolean | string => {
    if (value === null || value === undefined || value === '') return true

    const numValue = typeof value === 'string' ? parseFloat(value) : value

    return numValue >= min || `Must be at least ${min}`
  }
}

/**
 * Validate maximum value
 */
export function maxValue(max: number) {
  return (value: number | string): boolean | string => {
    if (value === null || value === undefined || value === '') return true

    const numValue = typeof value === 'string' ? parseFloat(value) : value

    return numValue <= max || `Must be at most ${max}`
  }
}

/**
 * Validate positive number (greater than 0)
 */
export function positive(value: number | string): boolean | string {
  if (value === null || value === undefined || value === '') return true

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  return numValue > 0 || 'Must be a positive number'
}

/**
 * Validate non-negative number (0 or greater)
 */
export function nonNegative(value: number | string): boolean | string {
  if (value === null || value === undefined || value === '') return true

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  return numValue >= 0 || 'Must be zero or greater'
}

/**
 * Validate integer (no decimals)
 */
export function integer(value: number | string): boolean | string {
  if (value === null || value === undefined || value === '') return true

  const numValue = typeof value === 'string' ? parseFloat(value) : value

  return Number.isInteger(numValue) || 'Must be a whole number'
}

/**
 * Validate phone number (Ethiopian format)
 */
export function phoneNumber(value: string): boolean | string {
  if (!value) return true

  // Remove spaces and dashes
  const cleaned = value.replace(/[\s-]/g, '')

  // Ethiopian phone number patterns:
  // - 10 digits starting with 0 (e.g., 0911234567)
  // - 12 digits starting with 251 (e.g., 251911234567)
  // - 9 digits (without leading 0)
  const phoneRegex = /^(0\d{9}|251\d{9}|\d{9})$/

  return phoneRegex.test(cleaned) || 'Invalid phone number'
}

/**
 * Validate URL format
 */
export function url(value: string): boolean | string {
  if (!value) return true

  try {
    new URL(value)
    return true
  } catch {
    return 'Invalid URL'
  }
}

/**
 * Validate date is not in the past
 */
export function futureDate(value: string | Date): boolean | string {
  if (!value) return true

  const dateObj = typeof value === 'string' ? new Date(value) : value
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  return dateObj >= today || 'Date must be today or in the future'
}

/**
 * Validate date is not in the future
 */
export function pastDate(value: string | Date): boolean | string {
  if (!value) return true

  const dateObj = typeof value === 'string' ? new Date(value) : value
  const today = new Date()
  today.setHours(23, 59, 59, 999)

  return dateObj <= today || 'Date must be today or in the past'
}

/**
 * Validate that two fields match (e.g., password confirmation)
 */
export function matches(target: any) {
  return (value: any): boolean | string => {
    return value === target || 'Fields do not match'
  }
}

/**
 * Validate unique value in array
 */
export function unique(existingValues: any[]) {
  return (value: any): boolean | string => {
    return !existingValues.includes(value) || 'This value already exists'
  }
}

/**
 * Validate Ethiopian TIN (Tax Identification Number)
 * Format: 10 digits
 */
export function ethiopianTIN(value: string): boolean | string {
  if (!value) return true

  const cleaned = value.replace(/\s/g, '')
  const tinRegex = /^\d{10}$/

  return tinRegex.test(cleaned) || 'Invalid TIN (must be 10 digits)'
}

/**
 * Validate that value is one of the allowed options
 */
export function oneOf(options: any[]) {
  return (value: any): boolean | string => {
    return options.includes(value) || `Must be one of: ${options.join(', ')}`
  }
}

/**
 * Combine multiple validators
 */
export function combine(...validators: ((value: any) => boolean | string)[]) {
  return (value: any): boolean | string => {
    for (const validator of validators) {
      const result = validator(value)
      if (result !== true) {
        return result
      }
    }
    return true
  }
}

/**
 * Validate that a number has at most N decimal places
 */
export function maxDecimals(max: number) {
  return (value: number | string): boolean | string => {
    if (value === null || value === undefined || value === '') return true

    const strValue = value.toString()
    const decimalIndex = strValue.indexOf('.')

    if (decimalIndex === -1) return true // No decimals

    const decimals = strValue.length - decimalIndex - 1

    return decimals <= max || `Maximum ${max} decimal place${max > 1 ? 's' : ''} allowed`
  }
}
