<template>
  <LayoutAuthenticated>
    <SectionMain>
      <FormField label="Add Brand">
        <FormControl v-model="form.name" />
        <FormControl v-model="form.product_type" :options="selectOptions" />
        <BaseButton @click="handleForm" label="Submit Record" />
      </FormField>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import axiosClient from '@/stores/axios'
import { useProductTypeStore } from '@/stores/productType'
import { storeToRefs } from 'pinia'
import FormControl from '@/components/form/FormControl.vue'
import FormField from '@/components/form/FormField.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useBrandStore } from '@/stores/brands'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import { ErrorMessage, Field, Form } from 'vee-validate'
import { useRoute, useRouter } from 'vue-router'
import * as yup from 'yup';
import DynamicForm from '@/components/DynamicForm.vue'
import SectionMain from '@/components/section/SectionMain.vue'

const productTypeStore = useProductTypeStore()
const brandStore = useBrandStore()
const { data } = storeToRefs(productTypeStore)
const schema = yup.object({
  email: yup.string().email().required('Email is required'),
  name: yup.string().required('Name is required'),
})
const formSchema = {
  fields: [
    {
      label: 'Your Name',
      name: 'name',
      as: 'input',
    },
    {
      label: 'Your Email',
      name: 'email',
      as: 'input',
    },
    {
      label: 'Your Password',
      name: 'password',
      as: 'input',
      type: "password",
      rules: yup.string().min(6).required('Password is required'),
    },
  ],
};
const onSubmitForm = (values) => console.log(JSON.stringify(values, null, 2));

const validateEmail = (value) => {
  // if the field is empty
  if (!value) {
    return 'This field is required';
  }
  // if the field is not a valid email
  const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
  if (!regex.test(value)) {
    return 'This field must be a valid email';
  }
  // All is good
  return true;
}

const router = useRouter()
const route = useRoute()

const handleForm = async (nef) => {
  console.log(form, 'clicked m ')
  console.log(nef, 'clicked ef')
  try {
    const resu = await brandStore.postData(form, 'brands')
    router.push('/brands')
  } catch (error) {
    console.log(error.status, 'REPOSNSSN BE BADO')
  }

}

onMounted(() => {
  productTypeStore.getProductType()
})



const selectOptions = computed(() => data.value.map(br => ({ id: br.id, name: br.name })))
// const selectOptions = computed(()=>data.value.map(br=> br.name))
setTimeout(() => console.log(selectOptions)
  , 1000)
const form = reactive({
  name: '',
  product_type: selectOptions.value,
})
// Form state and errors


const props = defineProps(['id'])

const errors = reactive({
  name: null,
  age: null,
  gender: null,
  subscribe: null,
  feedback: null
});

const isSubmitting = ref(false);
const flashMessage = ref(null);

// Basic form validation
const validateForm = () => {
  let isValid = true;

  // Reset errors
  Object.keys(errors).forEach(key => (errors[key] = null));

  if (!form.name) {
    errors.name = 'Name is required.';
    isValid = false;
  }

  if (!form.age) {
    errors.age = 'Age is required.';
    isValid = false;
  }

  if (!form.gender) {
    errors.gender = 'Gender is required.';
    isValid = false;
  }

  if (!form.feedback) {
    errors.feedback = 'Feedback is required.';
    isValid = false;
  }

  return isValid;
};

// Submit form handler with flash messages
const submitForm = async () => {
  if (!validateForm()) {
    flashMessage.value = { type: 'error', message: 'Please fix the errors in the form.' };
    return;
  }

  isSubmitting.value = true;
  flashMessage.value = null;

  try {
    console.log(form)
    const response = await axiosClient.post('/api/submit', form);

    flashMessage.value = { type: 'success', message: 'Form submitted successfully!' };
    console.log('Server Response:', response.data);

    // Clear form and errors after successful submission
    Object.keys(form).forEach(key => (form[key] = ''));
  } catch (error) {
    if (error.response && error.response.data.errors) {
      Object.keys(error.response.data.errors).forEach(key => {
        errors[key] = error.response.data.errors[key][0];
      });
    }
    flashMessage.value = { type: 'error', message: 'An error occurred while submitting the form.' };
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1rem;
}

.error-text {
  color: red;
  font-size: 0.875rem;
}

.flash-success {
  background-color: #d4edda;
  color: #155724;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border: 1px solid #c3e6cb;
}

.flash-error {
  background-color: #f8d7da;
  color: #721c24;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border: 1px solid #f5c6cb;
}
</style>
