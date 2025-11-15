/* eslint-disable no-unused-labels */
import { defineStore } from "pinia";
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import axiosClient from "./axios";
import { getRequest } from "./api";

export const useAuthStore = defineStore('auth', () => {
  const user = ref([]);
  const router = useRouter();

  const getAuthRoles = async () => await axiosClient.get('/roles')
  getAuthRoles().then(response => console.log(response))
  const register = async (userData) => {
    try {
      const { data } = await axiosClient.post('/register', userData);
      user.value = data;
      localStorage.setItem('authToken', data.token);
      console.log(data, 'FROM REGISTER STORE');

      // setUser(data);
      return data;
    } catch (error) {
      console.error('Registration error:', error);
      throw error;
    }
  };

  const data = ref([])

  const login = async (userData) => {
    try {
      console.log('Full URLKE AUTHENTICATION FILE:', axiosClient.defaults.baseURL + '/login');

      const { data } = await axiosClient.post('/login', userData);
      user.value = data;
      localStorage.setItem('authToken', data.user.token);
      localStorage.setItem('user', JSON.stringify(data.user.data));
      console.log('FROM LOGIN STORE', user.value);
      console.log('FROM LOGIN STORE', data);
      return data;
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  };

  const logout = async () => {
    try {
      const response = await axiosClient.post('/logout');
      loggingout();
      console.log('FROM LOGOUT STORE', response);
      return response;
    } catch (error) {
      console.error('Logout error:', error);
      throw error;
    }
  };

  const loggingout = () => {
    user.value = null;
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
    router.push({ name: 'login' });
  };

  // const getUser = getRequest({ data }, 'users')
  const getUser = async () => {
    try {
      data.value = (await getRequest('users')).data
    } catch (error) {
      console.log('random user fetching code', error)
    }
  }

  return {
    user,
    register,
    login,
    logout,
    loggingout,
    // setUser,
    getUser,
    data,
    getAuthRoles
  };
});
