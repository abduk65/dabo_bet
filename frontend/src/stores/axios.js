import axios from "axios";
import { useAuthStore } from "./authentication";
const axiosClient = axios.create({
  baseURL: 'http://localhost:8029/api'
})

axiosClient.interceptors.request.use(config => {
  // config.headers.Authorization = `Bearer ${authStore.user.token}`
  return config;
})

// Add this after your axiosClient creation
axiosClient.interceptors.request.use(config => {
  console.log('Full URL:', config.baseURL + config.url);
  return config;
});

export default axiosClient;
