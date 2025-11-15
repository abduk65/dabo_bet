import { getRequest, postRequest } from './api'
import { defineStore } from 'pinia'
import { ref } from 'vue'


export const useUnitStore = defineStore('unit', () => {
  const units = ref([])
  const loading = ref(false)
  const error = ref(null)

  const url = 'units'
  const data = ref([])

  const getUnits = async () => {
    try {
      data.value = (await getRequest(url)).data
    } catch (error) {
      console.log(error, "Error in Unit store")
    }
  }

  const createUnit = (incoming) => postRequest(incoming, url)

  return { units, loading, error, getUnits, data, createUnit }
})
