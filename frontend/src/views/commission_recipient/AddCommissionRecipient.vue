<template>
  <LayoutAuthenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="form.name" />
        </FormField>
        <FormField label="Branch">
          <FormControl v-model="form.branch_id" :options="branches" />
        </FormField>

        <BaseButton type="submit" label="Submit" />
      </form>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import FormControl from '@/components/form/FormControl.vue'
import FormField from '@/components/form/FormField.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useBranchStore } from '@/stores/branches' // You'll need to create this store
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient';

const router = useRouter()
const commissionRecipientStore = useCommissionRecipientStore()
const branchStore = useBranchStore()
const { branches } = storeToRefs(branchStore)

// Update form state to only include relevant fields
const form = reactive({
  name: '',
  branch_id: 0
});

onMounted(async () => {
  await branchStore.getBranches() // You'll need to implement this method in the store
})

const isSubmitting = ref(false);

const submitForm = async () => {
  const mels = commissionRecipientStore.postCommission(form)
  const success = await mels
  if (success.success) {
    router.push({ name: "CommissionRecipients" })
  }
  console.log(success)
};
</script>

<style scoped></style>
