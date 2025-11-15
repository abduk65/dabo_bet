import { defineStore } from 'pinia'
import { ref } from 'vue'
import { getRequest, postRequest, putRequest } from '@/stores/api'

export const useExpensesStore = defineStore('expense', () => {
  const data = ref([])
  const uri = 'expenses'
  const editUrl = '/expenses'
  const getExpenses = async () => {
    try {
      data.value = (await getRequest(uri)).data
    } catch (error) {
      console.log(error, "Error in Expenses store")
    }
  }
  const updateExpense = (incomingData) => putRequest(incomingData, editUrl);
  const createExpense = (incomingData) => postRequest(incomingData, uri)
  return { data, getExpenses, createExpense, updateExpense }
})
