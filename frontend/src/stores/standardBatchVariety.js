import { defineStore } from 'pinia'
import { getRequest, postRequest } from '@/stores/api'
import { ref } from 'vue'

export const useStandardBatchVarietyStore = defineStore("standard_batch_variety", () => {
  const data = ref([])

  const uri = "standardBatchVariety"
  const getStandardBatchVariety = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in StandardBatchVariety store")
    }
  }

  const createStandardBatchVariety = (incoming) => postRequest(incoming, uri)

  return { data, getStandardBatchVariety, createStandardBatchVariety }

})
