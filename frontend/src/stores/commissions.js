import { defineStore } from 'pinia'
import { ref } from 'vue'
import { getRequest, postRequest, putRequest } from '@/stores/api'

export const useCommissionStore = defineStore('commission', () => {
  const data = ref([])
  const uri = 'commissions'

  const getCommission = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in Commission store")
    }
  }
  const createCommission = (incomingData) => postRequest(incomingData, uri)
  const updateCommission = (payload) => putRequest(payload, uri)

  return { data, updateCommission, getCommission, createCommission }
})
