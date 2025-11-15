import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import axiosClient from './axios'

export const useInventoryAdjustmentStore = defineStore('inventoryAdjustment', () => {
  const inventoryAdjustments = ref([])
  const loading = ref(false)
  const error = ref()

  const fetchInventoryAdjustments = async () => {
    console.log('PPPOPO')
    loading.value = true
    try {
      // const response = await axios.get('/inventoryAdjustment')
      const uri = 'inventoryAdjustment'
      const response = await axiosClient.get(uri);
      console.log('INVENTORY ADJ', response)
      inventoryAdjustments.value = response.data
      return response.data
    } catch (error) {
      error.value = error.message
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchInventoryAdjustment = async (id) => {
    loading.value = true
    try {
      const response = await axios.get(`inventoryAdjustment/${id}`)
      return response.data
    } catch (error) {
      error.value = error.message
      throw error
    } finally {
      loading.value = false
    }
  }

  const createInventoryAdjustment = async (data) => {
    loading.value = true
    try {
      console.log(data, 'BEOFRE     ')

      const response = await axiosClient.post('inventoryAdjustment', data)
      console.log(response, 'INSISISISISIS')
      inventoryAdjustments.value.push(response.data)
      return response.data
    } catch (error) {
      error.value = error.message
      throw error
    } finally {
      loading.value = false
    }
  }

  const updateInventoryAdjustment = async (id, data) => {
    loading.value = true
    try {
      const response = await axios.put(`inventoryAdjustment/${id}`, data)
      const index = inventoryAdjustments.value.findIndex(item => item.id === id)
      if (index !== -1) {
        inventoryAdjustments.value[index] = response.data
      }
      return response.data
    } catch (error) {
      error.value = error.message
      throw error
    } finally {
      loading.value = false
    }
  }

  const deleteInventoryAdjustment = async (id) => {
    loading.value = true
    try {
      await axios.delete(`inventoryAdjustment/${id}`)
      inventoryAdjustments.value = inventoryAdjustments.value.filter(item => item.id !== id)
    } catch (error) {
      error.value = error.message
      throw error
    } finally {
      loading.value = false
    }
  }

  return { deleteInventoryAdjustment, updateInventoryAdjustment, createInventoryAdjustment, fetchInventoryAdjustment, fetchInventoryAdjustments, inventoryAdjustments, error, loading }

})
