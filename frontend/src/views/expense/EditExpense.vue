<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Edit Expense">
        <BaseButton to="/expenses" label="Back" />
      </SectionTitleLineWithButton>

      <CardBox class="mb-6" has-table>
        <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
          <ValidatedFormControl name="description" label="Description" />
          <ValidatedFormControl name="amount" type="number" label="Amount" />
          <ValidatedFormControl name="branch_id" label="Branch" :options="formattedBranchOptions" type="select"
            option-label="name" />
          <ValidatedFormControl name="user_id" label="User" :options="formattedUserOptions" type="select"
            option-label="name" />
          <ValidatedFormControl name="type" label="Type" :options="[
            { id: 'expected', name: 'Expected' },
            { id: 'unexpected', name: 'Unexpected' }
          ]" type="select" option-label="name" option-value="id" />
          <BaseButton type="submit" label="Update Expense" />
        </Form>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { Form } from 'vee-validate';
import * as Yup from 'yup';
import { computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue';
import SectionMain from '@/components/section/SectionMain.vue';
import ValidatedFormControl from '@/components/ValidatedFormControl.vue';
import CardBox from '@/components/cardbox/CardBox.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue';
import { mdiTableBorder } from '@mdi/js';
import { useExpensesStore } from '@/stores/expenses';
import { useBranchStore } from '@/stores/branches';
import { useAuthStore } from '@/stores/authentication';

const router = useRouter();
const route = useRoute();
const expensesStore = useExpensesStore();
const branchStore = useBranchStore();
const userStore = useAuthStore();

const schema = Yup.object({
  description: Yup.string().required('Description is required'),
  amount: Yup.number().required('Amount is required').min(0, 'Amount must be positive'),
  branch_id: Yup.mixed().required('Branch is required'),
  user_id: Yup.mixed().required('User is required'),
  type: Yup.string().required('Type is required'),
});

const initialData = computed(() => {
  return expensesStore.data.find(expense => expense.id == route.params.id);
});

const formattedBranchOptions = computed(() => {
  return branchStore.branches.map(branch => ({
    id: branch.id,
    name: branch.name
  }));
});

const formattedUserOptions = computed(() => {
  return userStore.data.map(user => ({
    id: user.id,
    name: user.name
  }));
});

onMounted(async () => {
  await Promise.all([
    expensesStore.getExpenses(),
    branchStore.getBranches,
    userStore.getUser()
  ]);
});

const onSubmit = async (values) => {
  try {
    await expensesStore.updateExpense(values);
    router.push('/expenses');
  } catch (error) {
    console.error('Failed to update expense:', error);
  }
};
</script>
