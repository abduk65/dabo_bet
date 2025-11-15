<template>
  <LayoutAuthenticated>
    <SectionMain>
      {{ initialData }}

      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit">
        <ValidatedFormControl name="name" placeholder="Enter branch name" />
        <ValidatedFormControl name="type" type="select" :options="typeOptions" placeholder="Select branch type" />
        <button type="submit" class="btn btn-primary">
          Update Branch
        </button>
      </Form>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { Form } from 'vee-validate';
import * as Yup from 'yup';
import { computed, onMounted, watch } from 'vue';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue';
import SectionMain from '@/components/section/SectionMain.vue';
import ValidatedFormControl from '@/components/ValidatedFormControl.vue';
import { useBranchStore } from '@/stores/branches';
import { useRouter } from 'vue-router';
const props = defineProps(['id']);
const branchStore = useBranchStore();
const router = useRouter();

const typeOptions = [
  { id: 'main', name: 'Main' },
  { id: 'sub', name: 'Sub' }
];

const schema = Yup.object({
  name: Yup.string().required('Branch name is required'),
  type: Yup.string().oneOf(['main', 'sub'], 'Please select a valid branch type').required('Branch type is required'),
});

onMounted(() => {
  branchStore.getBranches();
});

watch(() => branchStore.branches, (newValue, oldValue) => {
  console.log('Branches have been updated', newValue, oldValue);
});

const initialData = computed(() => {

  console.log('bebebebe')
  const branch = branchStore.branches.find(val => Number(val.id) === Number(props.id));
  console.log('branch, ', branch)
  if (branch) {
    return {
      name: branch.name,
      type: branch.type
    };
  }
  return null;
});

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    name: values.name,
    type: values.type
  };
  console.log('Submitting:', updateData, props);
  const response = await branchStore.editBranch(updateData);
  console.log(response, 'response')
  if (response.status === 200) {
    router.push('/branches')
  };
}
</script>

<style scoped></style>
