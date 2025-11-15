<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
        <ValidatedFormControl name="branch_id" :options="formattedBranchOptions" type="select" option-label="name"
          placeholder="Select Branch" />
        <ValidatedFormControl name="amount" type="number" placeholder="Enter amount" />

        <button type="submit" class="btn btn-primary">
          Update Cash Collection
        </button>
      </Form>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { Form } from 'vee-validate';
import * as Yup from 'yup';
import { computed, onMounted } from 'vue';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue';
import SectionMain from '@/components/section/SectionMain.vue';
import ValidatedFormControl from '@/components/ValidatedFormControl.vue';
import { useBranchStore } from '@/stores/branches';
import { useCashCollectedStore } from '@/stores/cashCollected';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const branchStore = useBranchStore();
const cashCollectedStore = useCashCollectedStore();

const schema = Yup.object({
  branch_id: Yup.mixed().required('Branch is required'),
  amount: Yup.number().required('Amount is required').positive('Amount must be positive'),
});

onMounted(async () => {
  await Promise.all([
    cashCollectedStore.getCashCollected(),
    branchStore.getBranches()
  ]);
});

const initialData = computed(() => {
  const cashCollection = cashCollectedStore.data.find(val => Number(val.id) === Number(props.id));
  if (cashCollection) {
    return {
      branch_id: cashCollection.branch_id,
      amount: cashCollection.amount,
    };
  }
  return null;
});

// Format options for the branch select
const formattedBranchOptions = computed(() =>
  branchStore.branches.map(branch => ({
    id: branch.id,
    name: branch.name
  }))
);

const router = useRouter();
const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    branch_id: values.branch_id,
    amount: values.amount,
  };

  const response = await cashCollectedStore.putCashCollected(updateData, 'cashCollected');
  console.log(response, 'ram')
  if (response.id) {
    router.push('/cashCollected');
  }
};
</script>

<style scoped></style>
