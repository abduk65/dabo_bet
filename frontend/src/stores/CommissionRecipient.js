import { defineStore } from 'pinia'
import { ref } from 'vue'
import { getRequest, postRequest, putRequest } from '@/stores/api'
import axios from 'axios'
import axiosClient from './axios'

export const useCommissionRecipientStore = defineStore('commission_recipient', () => {
  const data = ref([])
  const uri = 'commissionRecipients'
  const getCommission = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in CommissionRecipient store")
    }
  }
  const postCommission = (payload) => postRequest(payload, uri)
  const putCommission = (payload) => putRequest(payload, uri)

  const loading = ref(false)
  const error = ref(null)


  async function deleteCommissionRecipient(id) {
    console.log(id, "aosdirei")
    try {
      loading.value = true
      console.log(id, "<Rep></Rep>")
      const response = await axiosClient.delete(`${uri}/${id}`)
      console.log(response, "<Rep></Rep>")
      data.value = data.value.filter(item => item.id !== id)
    } catch (error) {
      error.value = error.message
    } finally {
      loading.value = false
    }
  }

  return {
    data,
    getCommission,
    postCommission,
    putCommission,
    loading,
    error,
    deleteCommissionRecipient
  }
})
