<template>
  <layout-authenticated>
<!--    <Veevalidate></Veevalidate>-->

    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField>
          <FormField label="Name">
            <FormControl v-model="branch.name" :error="errors.name" />
          </FormField>
          <FormField label="Branch Type">
            <FormControl v-model="branch.type" :options="branchTypes" :error="errors.type" />
          </FormField>
          <BaseButton type="submit" label="Submit" :loading="isSubmitting" />
        </FormField>
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
import { useBranchStore } from '@/stores/branches'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import Veevalidate from '@/components/Veevalidate.vue'
import { useAuthStore } from '@/stores/authentication'

const router = useRouter()
const route = useRoute()
const branchStore = useBranchStore()
const { error } = storeToRefs(branchStore)
const branchId = computed(() => route.params.id)
const isEditing = computed(() => !!branchId.value)
const roles = ()=> useAuthStore().getAuthRoles()

const props = defineProps(['id'])

console.log('DATA RECEIVED FROM ROUTING CASE', route, props)

const branchTypes = [
  {
    name: "Main",
    id: 'main'
  },
  {
    name: "Sub",
    id: 'sub'
  }
]


const createBranch = () => {}

const branch = reactive({
  name: "", type: ""
})

const errors = reactive({ name: "", type: "" })
const isSubmitting = ref(false)

const submitForm = async (e) => {
  try {
    const response = await branchStore.createBranch(branch)
    if (response.status === 200) {
      console.log(response, "response")
      router.push('/branches')
    }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
