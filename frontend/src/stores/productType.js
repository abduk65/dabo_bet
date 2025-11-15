import { defineStore } from 'pinia'
import { getRequest, postRequest, putRequest } from '@/stores/api'
import { ref } from 'vue'

export const useProductTypeStore = defineStore("product_type", () => {
  const data = ref([])

  const uri = "productType"
  const getProductType = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in ProductType store")
    }
  }
  const createProductType = (incoming) => postRequest(incoming, uri)
  const updateProductType = (incoming) => putRequest(incoming, uri)

  return { data, getProductType, createProductType, updateProductType }
})
