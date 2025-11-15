import { defineStore } from 'pinia'
import axiosClient from './axios'
import { getRequest, postRequest, putRequest, deleteRequest } from './api'
import { ref } from 'vue'


export const useCashCollectedStore = defineStore('cashCollected', () => {

  const data = ref([])

  const getCashCollected = async () => {
    try {
      data.value = (await getRequest('cashCollected')).data
    } catch (error) {
      console.log(error, "Error in CashCollected store")
    }
  }

  const createCashCollected = (incoming) => postRequest(incoming, 'cashCollected')

  const putCashCollected = (updatedData, uri) => putRequest(updatedData, uri)

  const deleteCashCollected = async (id) => {
    try {
      console.log(`cashCollected/${id}`, 'RPPPPPPPPPO')
      await deleteRequest('cashCollected', id)
      // Remove the deleted item from the local data
      data.value = data.value.filter(item => item.id !== id)

    } catch (error) {
      console.log(error, "Error deleting cash collection")
      throw error
    }
  }

  return {
    data,
    getCashCollected,
    createCashCollected,
    putCashCollected,
    deleteCashCollected
  }

})
