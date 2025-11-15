<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
        <ValidatedFormControl name="name" placeholder="Enter recipient name" />
        <ValidatedFormControl name="branch_id" :options="formattedBranchOptions" type="select" option-label="name" />

        <button type="submit" class="btn btn-primary">
          Update Commission Recipient
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
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const branchStore = useBranchStore();
const commissionRecipientStore = useCommissionRecipientStore();

const schema = Yup.object({
  name: Yup.string().required('Name is required'),
  branch_id: Yup.mixed().required('Branch is required'),
});

onMounted(async () => {
  await Promise.all([
    commissionRecipientStore.getCommission(),
    branchStore.getBranches
  ]);
});

const initialData = computed(() => {
  const recipient = commissionRecipientStore.data.find(val => Number(val.id) === Number(props.id));
  if (recipient) {
    return {
      name: recipient.name,
      branch_id: recipient.branch?.id || recipient.branch_id
    };
  }
  return null;
});

const formattedBranchOptions = computed(() =>
  branchStore.branches.map(branch => ({
    id: branch.id,
    name: branch.name
  }))
);

const router = useRouter()

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    name: values.name,
    branch_id: values.branch_id
  };

  console.log('Submitting:', updateData);
  const success = await commissionRecipientStore.putCommission(updateData);
  if (success) {
    router.push({ name: "CommissionRecipients" });
  }
};
</script>
