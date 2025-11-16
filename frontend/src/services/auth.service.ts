import api from './api'
import type { LoginCredentials, LoginResponse, User, ApiResponse } from '@/types'

export const authService = {
  async login(credentials: LoginCredentials): Promise<LoginResponse> {
    const response = await api.post<LoginResponse>('/login', credentials)
    return response.data
  },

  async logout(): Promise<void> {
    await api.post('/logout')
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
  },

  async getCurrentUser(): Promise<User> {
    const response = await api.get<ApiResponse<User>>('/user')
    return response.data.data
  },

  async changePassword(currentPassword: string, newPassword: string, newPasswordConfirmation: string): Promise<void> {
    await api.post('/change-password', {
      current_password: currentPassword,
      new_password: newPassword,
      new_password_confirmation: newPasswordConfirmation
    })
  },

  saveToken(token: string): void {
    localStorage.setItem('auth_token', token)
  },

  saveUser(user: User): void {
    localStorage.setItem('user', JSON.stringify(user))
  },

  getToken(): string | null {
    return localStorage.getItem('auth_token')
  },

  getUser(): User | null {
    const user = localStorage.getItem('user')
    return user ? JSON.parse(user) : null
  },

  isAuthenticated(): boolean {
    return !!this.getToken()
  }
}
