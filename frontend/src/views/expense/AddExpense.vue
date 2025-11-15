<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Description">
          <FormControl v-model="expense.description" :error="errors.description" />
        </FormField>

        <FormField label="Amount">
          <FormControl v-model.number="expense.amount" type="number" :error="errors.amount" />
        </FormField>

        <FormField label="Branch">
          <FormControl v-model="expense.branch_id" :options="branches" as="select" :error="errors.branch">

          </FormControl>
        </FormField>

        <FormField label="User">
          <FormControl v-model="expense.user_id" :options="users" as="select" :error="errors.user"> </FormControl>
        </FormField>

        <FormField label="Type">
          <FormControl v-model="expense.type"
            :options="[{ id: 'expected', name: 'Expected' }, { id: 'unexpected', name: 'Unexpected' }]" as="select"
            :error="errors.type">
          </FormControl>
        </FormField>

        <BaseButton type="submit" label="Add Expense" :loading="isSubmitting" />
      </form>
    </SectionMain>
  </layout-authenticated>
</template>

<script setup>
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import FormField from '@/components/form/FormField.vue'
import FormControl from '@/components/form/FormControl.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { ref, onMounted } from 'vue';
import { useExpensesStore } from '@/stores/expenses';
import { useBranchStore } from '@/stores/branches';
import { useAuthStore } from '@/stores/authentication';
import { storeToRefs } from 'pinia';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';

const expensesStore = useExpensesStore();
const branchStore = useBranchStore();
const userStore = useAuthStore();
const router = useRouter();

const expense = reactive({
  description: '',
  amount: null,
  branch_id: null,
  user_id: null,
  type: 'expected'
});

const { data: users } = storeToRefs(userStore);
const { data: branches } = storeToRefs(branchStore);

const errors = reactive({ description: '', amount: '', branch: '', user: '', type: '' });
const isSubmitting = ref(false);

const validateForm = () => {
  let isValid = true;
  // errors.description = expense.value.description.trim() ? '' : 'Description is required';
  // errors.amount = expense.value.amount ? '' : 'Amount is required';
  // errors.branch = expense.value.branch ? '' : 'Branch is required';
  // errors.user = expense.value.user ? '' : 'User is required';
  // errors.type = expense.value.type ? '' : 'Type is required';

  if (errors.description || errors.amount || errors.branch || errors.user || errors.type) {
    isValid = false;
  }

  return isValid;
};

const submitForm = async () => {
  // if (!validateForm()) return;
  isSubmitting.value = true;

  try {
    await expensesStore.createExpense(expense);
    // Optionally, refresh the expense list
    router.push('/expenses');
  } catch (error) {
    console.log(error, 'ERROR');
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(async () => {
  await branchStore.getBranches;
  await userStore.getUser();
});
</script>
