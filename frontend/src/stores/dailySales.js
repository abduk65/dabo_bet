import { defineStore } from "pinia";
import axiosClient from "./axios";
import { computed, reactive, ref } from 'vue'
import { id } from 'date-fns/locale'
import axios from 'axios'
export const useDailySalesStore = defineStore('dailySales', () => {
  const sales = ref([])
  const getDailySales = async () => {
    try {
      const { data } = await axiosClient.get('/dailySales')
      sales.value = data
    } catch (error) {
      console.log(error, "ERROR")
    }
  }

  const createDailySales = async (input) => {
    const { data } = await axiosClient.post('/createDailySales', input, {
      headers: {
        'Content-Type': 'application/json'
      }
    }
    )
    console.log(data, 'DATA FROM BACKEND FOR SALES DATA')
    //  const response = await axios.post('http://localhost:8029/api/createDailySales', input, {
    //    headers: {
    //      'Content-Type': 'application/json'
    //    }
    //  });
    //  console.log(response)
  }

  return { getDailySales, sales, createDailySales }

})
