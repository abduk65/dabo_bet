<template>
  <div>
    <div class="mb-3">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>amount</th>
            <th>Expected</th>
          </tr>
        </thead>
        <tbody>
        <tr v-for="(expense, index) in expenses" :key="index" >
          <td>
            <input
              v-model="expense.name"
              class="bg-gray-800"
              placeholder="Expense name"
              @input="updateExpense(index, 'name', $event.target)"
            />
          </td>
          <td>
            <input
              v-model="expense.amount"
              type="number"
              class="bg-gray-800"
              placeholder="Amount"
              @input="updateExpense(index, 'amount', $event.target)"
            />
          </td>
          <td> <input type="checkbox" v-model="expense.unexpected" ></td>
          <td><BaseButton :id="index" @click="removeExpense(index)" class="ml-8" :icon="mdiCloseBoxMultipleOutline" /></td>
        </tr>
        <tr v-if="expenses.length>0"><td> Sum Equals = {{expenseSum}}</td></tr>
        </tbody>
      </table>
    </div>
    <BaseButton label="Add A new Expense" class="mr-4" @click="addExpense"></BaseButton>
<!--    <BaseButton label="Calculate Expense" @click="calculateExpense"></BaseButton>-->
  </div>
</template>

<script setup>
import { computed, isReactive, isRef, ref, unref, watch } from 'vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { mdiCloseCircle } from '@mdi/js/commonjs/mdi'
import { mdiCloseBoxMultipleOutline } from '@mdi/js/commonjs/mdi'
import { useBranchStore } from '@/stores/branches'

const  emit = defineEmits(['expenses-updated'])
const props = defineProps({
  branchData:{
    type: Object,
    required: true
  }
})
const initialExpense = { name: '', amount: '' }
// const expenses = ref([initialExpense]);
const removeExpense = (index) => {
  expenses.splice(index, 1)
};

const expenseSum = computed(()=>expenses.reduce((acc, val)=> acc += Number(val.amount), 0))
const branchData = props.branchData
const expenses = branchData.expenses

watch(
  expenses, // Directly watch the expenses ref
  (newVal, oldVal) => {
    console.log('Expenses changed: ', newVal); // Log the updated array
    console.log('NEW VALUE BRANCHDATA: ', props.branchData); // Log the updated array
    console.log(branchData.expenses, 'BRANCHDATA EXPENSES')
    console.log(useBranchStore().completeSalesData, 'COMPLETE SALESDATA')
  },
  { deep: true } // Add deep: true to watch changes within the array itself
);
const addExpense = () => {
  console.log(isReactive(expenses), 'TRYE')
  expenses.push({ name: '', amount: 0, unexpected: false });
  emit('expenses-updated', expenses);
};

const updateExpense = (index, field, value) => {
  console.log(index, field, value, '----------------------');
  expenses[index][field] = value.value;
  emit('expenses-updated', expenses);
};

const calculateExpense = () => {
  console.log(expenses);
}
</script>

<style scoped>
/* Add styling for your inputs and buttons */
</style>
