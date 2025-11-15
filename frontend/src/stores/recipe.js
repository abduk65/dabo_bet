import { defineStore } from 'pinia'
import { getRequest, postRequest, putRequest } from '@/stores/api'
import { ref } from 'vue'
import axiosClient from './axios'

export const useRecipeStore = defineStore('recipe', {
  state: () => ({
    data: [],
    loading: false,
    error: null
  }),

  actions: {
    async getRecipe() {
      try {
        this.loading = true
        const response = await axiosClient.get('/recipe')
        this.data = response.data
        return this.data
      } catch (error) {
        console.error('Error fetching recipes:', error)
        this.error = error
        throw error
      } finally {
        this.loading = false
      }
    },

    async createRecipe(recipeData) {
      try {
        this.loading = true
        const response = await axiosClient.post('/recipe', recipeData)
        return response.data
      } catch (error) {
        console.error('Error creating recipe:', error)
        this.error = error
        throw error
      } finally {
        this.loading = false
      }
    },

    async getProducts() {
      try {
        const response = await axiosClient.get('/products')
        return response.data
      } catch (error) {
        console.error('Error fetching products:', error)
        throw error
      }
    },

    async getInventoryItems() {
      try {
        const response = await axiosClient.get('/inventoryItem')
        return response.data
      } catch (error) {
        console.error('Error fetching inventory items:', error)
        throw error
      }
    },

    async getUnits() {
      try {
        const response = await axiosClient.get('/units')
        return response.data
      } catch (error) {
        console.error('Error fetching units:', error)
        throw error
      }
    }
  }
})
