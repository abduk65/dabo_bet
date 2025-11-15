import { defineStore } from "pinia";
import axiosClient from "./axios";
import { reactive, ref } from 'vue'
import { getRequest, postRequest, putRequest } from './api'

export const useProductsStore = defineStore('productsStore', () => {

  const data = ref([])
  const uri = 'products'
  // const getProducts = getRequest({ data }, uri)

  const getProducts = () => {
    try {
      axiosClient.get('/products').then(response => {
        data.value = response.data
        console.log(data, 'fetch ketederege behuala')
      })
    } catch (e) {
      console.error('Couldnt fetch Products Data')
    }
  }

  const createProduct = (incomingData) => postRequest(incomingData, uri)
  const updateProduct = (incomingData) => putRequest(incomingData, uri)

  return { data, getProducts, createProduct, updateProduct }

})
