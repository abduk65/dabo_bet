import { defineStore } from 'pinia'
import axiosClient from './axios'
import { computed, onMounted, reactive, ref, watch, watchEffect } from 'vue'
import { useProductsStore } from '@/stores/products'
import { getRequest } from '@/stores/api'

export const useBranchStore = defineStore('branchStore', () => {

  // const data = ref([])
  const branches = ref([])
  const loading = ref(false);
  const error = ref(null);
  // const data = ref([])
  const getBranches = () => {
    loading.value = true
    error.value = null
    try {
      const response =  axiosClient.get('/branches').then(response=>
        branches.value = response.data
      )
    } catch (e) {
      error.value = e.message || 'An error occurred while fetching branches'
      console.error('Error fetching branches:', e)
    } finally {
      loading.value = false
    }
  }

  // const retrieveBranch = getRequest({ data }, 'branches')

  const createBranch = async (input) => {
    loading.value = true
    error.value = null
    try {
      const response = await axiosClient.post('/branches', input)
      branches.value.push(response.data)
      return response
    } catch (e) {
      error.value = e.message || 'an error occurred while creating branch'
      console.error('Error creating branches:', e);
      throw e
    } finally {
      loading.value = false
    }
  }

  const deleteBranch = async (id) => {
    console.log(id, "DELETED BRANCH ID");
    loading.value = true
    error.value = null
    try {
      await axiosClient.delete(`/branches/${id}`)
      branches.value = branches.value.filter(branch => branch.id !== id)
    } catch (e) {
      error.value = e.message || 'An error occurred while deleting the branch'
      console.error('Error deleting branch:', e)
      throw e
    } finally {
      loading.value = false
    }
  }

  const editBranch = async ( updatedData) => {
    loading.value = true
    error.value = null

    try {
      console.log(updatedData, 'update data')
      const response = await axiosClient.put(`/branches/${updatedData.id}`, updatedData)
      return response
    } catch (e) {
      error.value = e.message || 'An error occurred while updating the branch'
      console.error('Error updating branch:', e)
      throw e
    } finally {
      loading.value = false
    }
  }

  const activeBranch = ref();
  const switchBranch = (branchId) => {
    const selectedBranch = branches?.value.find(branch => branch.id === branchId);
    if (selectedBranch) {
      activeBranch.value = selectedBranch;
    } else {
      console.error(`Branch with ID ${branchId} not found`);
    }
  };

  const randomHex = () => '#' + Math.floor(Math.random() * 16777215).toString(16);
  const withBg = computed(() =>
    branches?.value.map(branch => ({ [branch.id]: randomHex() }))
  )

  const bgBranch = computed(() => {
    const activeBranchObj = withBg.value.find(branchId =>Object.keys(branchId)[0] === String(activeBranch.value.id));
    return activeBranchObj ? activeBranchObj[Object.keys(activeBranchObj)[0]] : "";
  })

  const productStore = useProductsStore();
  onMounted( ()=> {
       productStore.getProducts()
       getBranches()
    }
  )
   function createBranchData(branchName) {
      // TODO: FIX THIS CALL TO OPTIMIZE IT BECAUSE IT IS MAKING THE SALES PAGE HANG BEFORE LOADING. REMOVE IT AND ONLY FETCH THE PRODUCTS ONCE. THIS IS DOING MULTIPLE BACKEND REQUESTS FOR EVERY INSTANCE ALMOST
    const dataProper = productStore.data.map(product => {
      return {
        id: product.id,
        name: product.name,
        price: product.unit_price,
        carryover: 0,
        quantity: 0, // initialized to zero
        commissionQuantityCSV: '', // initialized to zero
        selectedCommission: []
      }
    })

    return reactive({
      salesData: dataProper,
      expenses: [],
      cashCollected: 0,
      date: new Date(),
      branch: branches?.value[branchName-1]
    })
  }
  const loadStoredData = () => {
    const storedData = localStorage.getItem('completeSalesData');
    return storedData ? JSON.parse(storedData) : {};
  };

  // TODO: Replace empty object with loadStoredData AFTER SHIT STARTS WORKING

  const completeSalesData = reactive(new Proxy({},  {
     get(target, branchName) {
      if (!(branchName in target)) {
        target[branchName] =  createBranchData(branchName)
      }
      return target[branchName]
    }
  }))

  const getBranchData =  (branchName) => {
    return  completeSalesData[branchName]
  }
  const addBranch = (branchName) => {
    if (!completeSalesData[branchName]) {
      completeSalesData[branchName] = createBranchData();
    }
  };
  function throttle(fn, delay) {
    let lastCall = 0;

    return function (...args) {
      const now = Date.now();
      if (now - lastCall >= delay) {
        lastCall = now;
        fn.apply(this, args);
      }
    };
  }

  const throttledSave = throttle(() => {
    localStorage.setItem('completeSalesData', JSON.stringify(completeSalesData));
  }, 1000); // Throttle to save every 10 seconds

  // Watch and autosave to local storage
  // watch(
  //   () => newSalesData,
  //   throttledSave,
  //   { deep: true }
  // );
  const updateSales = (upDate) => {
    // newSalesData[activeBranch.value] = {}
    if (!activeBranch.value || !completeSalesData[activeBranch.value.id]) {
      console.error('Invalid active branch or sales data for the branch not found');
      return;
    }
    completeSalesData[activeBranch.value].salesData = upDate
    const branch = completeSalesData[activeBranch.value || 'TERA'];
    if (branch) {
      branch.salesData = upDate;
    } else {
      console.error(`Branch ${activeBranch.value} not found.`);
    }
  };

  const updateExpenses = (newExpense) => {
  };

  const updateCashCollected = (newAmount) => {
  };
  return {
    getBranches, deleteBranch, editBranch, loading, error, createBranch, activeBranch,
    switchBranch,
    branches,
    bgBranch,
    updateSales,
    updateExpenses,
    updateCashCollected,
    completeSalesData,
    getBranchData,
    addBranch,
  }
})

// const branches = ref([])
// const salesData = ref([])
// const getBranches = async () => {
//   loading.value = true;
//   error.value = null;
//
//   try {
//     const { data } = await axiosClient.get('/branches')
//     branches.value = data
//   } catch (error) {
//     error.value = error;
//     console.error('Error fetching branches:', error)
//   }finally {
//     loading.value = false;
//   }
// }

/**
 * TODO: CLAUDE WAY IN THE FUTURE IF I EVER ENCOUNTER A BIGGER PROBLEM WITH THE CURRENT APPROACH
 */
