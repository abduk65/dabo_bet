import { ref } from "vue";
import { defineStore } from "pinia";
import { getRequest, postRequest, putRequest } from "@/stores/api";

export const useDailyInventoryOut = defineStore('daily_inventory_out', () => {
  const data = ref([])
  const uri = 'dailyInventoryOut'
  const getDailyInventoryOut = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in DailyInventoryOut store")
    }
  }
  const createDailyInventoryOut = (postData) => postRequest(postData, uri)
  const putData = (updatedData, uri) => putRequest(updatedData, uri)
  return { getDailyInventoryOut, data, createDailyInventoryOut, putData }
})
