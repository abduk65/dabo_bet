import { defineStore } from 'pinia'
import { ref } from 'vue'
import axiosClient from '@/stores/axios'
import { getRequest, postRequest, deleteRequest, putRequest } from '@/stores/api'
// import { getRequest, postRequest, deleteRequest, putRequest } from '@/stores/api'

export const useBrandStore = defineStore('brands_store', () => {

  const data = ref([])
  const uri = 'brands'
  // const getBrands = getRequest({ data }, uri)
  const getBrands = async () => {
    try {
      data.value = (await getRequest(uri)).data
      return (await getRequest(uri)).data
    } catch (error) {
      console.log(error, " INSIDE THE BRAND STORE")
    }
  }

  const postData = (postData, postUrl) => postRequest(postData, postUrl)
  const putData = (putData, putUrl) => putRequest(putData, putUrl)
  const deleteData = (id) => deleteRequest(uri, id)
  return { getBrands, data, postData, deleteData, putData }
})
