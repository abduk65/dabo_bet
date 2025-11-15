// import { computed, onMounted, reactive, ref, unref, watch } from 'vue'
// import { useBranchStore } from '@/stores/branches'
// import BranchesView from '@/views/BranchView.vue'
// import { nextTick } from 'vue'
//
// // Composable for managing branch switching logic
// export function useBranchSwitcher() {
//   // Keep track of the active branch (default to the first branch)
//   const branchStore = useBranchStore()
//   // onMounted(async ()=>{
//   //   await branchStore.getBranches;
//   //   await nextTick();
//   //   branches.value = branchStore.data;
//   // })
//
//   // const branches = ref([])
//   const branches = ref([
//     {
//       "id": 1,
//       "name": "saris",
//       "type": "main",
//       "created_at": "2024-07-19T08:58:42.000000Z",
//       "updated_at": "2024-07-19T08:58:42.000000Z"
//     },
//     {
//       "id": 2,
//       "name": "tach_bet",
//       "type": "sub",
//       "created_at": "2024-07-19T08:58:42.000000Z",
//       "updated_at": "2024-07-19T08:58:42.000000Z"
//     },
//     {
//       "id": 3,
//       "name": "worku sefer",
//       "type": "sub",
//       "created_at": "2024-07-19T11:07:56.000000Z",
//       "updated_at": "2024-07-19T11:07:56.000000Z"
//     },
//     {
//       "id": 4,
//       "name": "Aman",
//       "type": "sub",
//       "created_at": "2024-08-19T13:50:59.000000Z",
//       "updated_at": "2024-08-19T13:50:59.000000Z"
//     },
//     {
//       "id": 5,
//       "name": "aldof",
//       "type": "sub",
//       "created_at": "2024-08-19T13:51:42.000000Z",
//       "updated_at": "2024-08-19T13:51:42.000000Z"
//     },
//     {
//       "id": 6,
//       "name": "arada",
//       "type": "main",
//       "created_at": "2024-08-19T13:54:21.000000Z",
//       "updated_at": "2024-08-19T13:54:21.000000Z"
//     }
//   ])
//   const activeBranch = ref();
//   const switchBranch = (branchId) => {
//     const selectedBranch = branches?.value.find(branch => branch.id === branchId);
//     if (selectedBranch) {
//       console.log("Setting activeBranch:", selectedBranch);
//       activeBranch.value = selectedBranch;
//     } else {
//       console.error(`Branch with ID ${branchId} not found`);
//     }
//
//   };
//
//   setTimeout(() => { console.log(activeBranch, 'branchiyans mnshe new BE TRACE MUSIC') }, 6400)
//   const randomHex = () => '#' + Math.floor(Math.random() * 16777215).toString(16);
//   const withBg = computed(() =>
//     branches?.value.map(branch => ({ [branch.id]: randomHex() }))
//   )
//
//   const bgBranch = computed(() => {
//     const activeBranchObj = withBg.value.find(branchId =>
//       Object.keys(branchId)[0] === String(activeBranch.value.id)
//     );
//     return activeBranchObj ? activeBranchObj[Object.keys(activeBranchObj)[0]] : "";
//   })
//
//   function createBranchData() {
//     return {
//       salesData: [],
//       expenses: [],
//       cashCollected: [],
//       date: new Date(),
//     }
//   }
//   const loadStoredData = () => {
//     const storedData = localStorage.getItem('newSalesData');
//     return storedData ? JSON.parse(storedData) : {};
//   };
//
//   // replace empty object with loadStoredData
//
//   const encapsulatingNewSalesData = reactive(new Proxy({}, {
//     get(target, branchName) {
//       if (!(branchName in target)) {
//         target[branchName] = createBranchData()
//       }
//       return target[branchName]
//     }
//   }))
//   const frozenBranch = computed(() => activeBranch.value);
//   setTimeout(() => {
//     console.log("Frozen branch value after timeout:", frozenBranch.value);
//   }, 6400);
//
//
//   watch(frozenBranch, (newVal, oldVal) => {
//     console.log('FREEEZEEEEEEEEE: freeze branch changed', oldVal, '->', newVal);
//   }, {deep:true})
//
//
//   watch(activeBranch, (newVal, oldVal) => {
//     console.log('Watcher: activeBranch changed', oldVal, '->', newVal);
//   }, { deep: true });
//   const getBranchData = (branchName) => {
//     return encapsulatingNewSalesData[branchName]
//   }
//
//   const addBranch = (branchName) => {
//     if (!encapsulatingNewSalesData[branchName]) {
//       encapsulatingNewSalesData[branchName] = createBranchData();
//     }
//   };
//
//   const logNewSalesData = () => console.log(encapsulatingNewSalesData, 'DEDICATED LOGGER FUNCTION')
//
//   function throttle(fn, delay) {
//     let lastCall = 0;
//
//     return function (...args) {
//       const now = Date.now();
//       if (now - lastCall >= delay) {
//         lastCall = now;
//         fn.apply(this, args);
//       }
//     };
//   }
//
//   const throttledSave = throttle(() => {
//     localStorage.setItem('newSalesData', JSON.stringify(encapsulatingNewSalesData));
//   }, 1000); // Throttle to save every 10 seconds
//
//   // Watch and autosave to local storage
//   // watch(
//   //   () => encapsulatingNewSalesData,
//   //   throttledSave,
//   //   { deep: true }
//   // );
//   const updateSales = (upDate) => {
//     // encapsulatingNewSalesData[activeBranch.value] = {}
//     if (!activeBranch.value || !encapsulatingNewSalesData[activeBranch.value.id]) {
//       console.error('Invalid active branch or sales data for the branch not found');
//       return;
//     }
//     // encapsulatingNewSalesData[activeBranch.value].salesData = upDate
//     // const branch = encapsulatingNewSalesData[activeBranch.value || 'TERA'];
//     // if (branch) {
//     //   branch.salesData = upDate;
//     // } else {
//     //   console.error(`Branch ${activeBranch.value} not found.`);
//     // }
//   };
//   // watch(()=> activeBranch, (newBranch, oldBranch) => {
//   //   console.log(`Branch switched from 22 ${oldBranch.value} to ${newBranch.value}`);
//   // }, {  deep: true });
//   const updateExpenses = (newExpense) => {
//   };
//
//   const updateCashCollected = (newAmount) => {
//   };
//
//   return {
//     activeBranch,
//     switchBranch,
//     branches, bgBranch,
//     updateSales,
//     updateExpenses,
//     updateCashCollected,
//     newSalesData: encapsulatingNewSalesData,
//     getBranchData,
//     addBranch,
//     logNewSalesData
//   };
// }
