import { defineStore } from 'pinia'
import { getRequest, postRequest, putRequest } from '@/stores/api'
import { ref } from 'vue'

export const useInventoryItemStore = defineStore("inventory_item", () => {
  const data = ref([])
  const uri = "inventoryItem"
  const getInventoryItem = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in InventoryItem store")
    }
  }
  const createInventoryItem = (incoming) => postRequest(incoming, uri)

  const putData = (updatedData, url_link) => putRequest(updatedData, url_link)

  return { data, getInventoryItem, createInventoryItem, putData }
})
