<template>
<div>
  <form >
    <!-- Name Field -->
<!--    <Form @submit="handleSubmit" >-->
<!--      <Field label="Name" name="email" :rules="validateEmail" />-->
<!--      <ErrorMessage name="email" ></ErrorMessage>-->
<!--      <button type="submit">Submit</button>-->
<!--    </Form>-->

    <DynamicForm :schema="formSchema"></DynamicForm>

    <!-- Email Field -->
<!--    <div>-->
<!--      <label for="email">Email:</label>-->
<!--      <Field id="email" name="email" rules="required|email" v-slot="{ field, errors }">-->
<!--        <input v-bind="field" type="email" />-->
<!--        <span v-if="errors[0]">{{ errors[0] }}</span>-->
<!--      </Field>-->
<!--    </div>-->

    <!-- Submit Button -->
  </form>

  <!-- Display Submitted Data -->
<!--  <div v-if="submittedData">-->
<!--    <h3>Form Submitted</h3>-->
<!--    <p>Name: {{ submittedData.name }}</p>-->
<!--    <p>Email: {{ submittedData.email }}</p>-->
<!--  </div>-->
</div>
</template>
<script setup>
import { ErrorMessage, Field, Form } from 'vee-validate'
import DynamicForm from '@/components/DynamicForm.vue'
import * as yup from 'yup'
const formSchema = {
  fields : [
    {
      label: 'Name',
      name: 'Nmae',
      as: 'input',
      rules:yup.string().required('Name is required'),
    },
    {
      label: 'Your Email',
      name: 'email',
      as: 'input',
      rules:yup.string().email().required('Email is required'),
    },
    {
      label: 'Your Password',
      name: 'password',
      as: 'input',
      type: 'password', rules: yup.string().min(5).required('Password is required'),
    }
  ]
}

const submittedData = {name: '', email: ''}
const handleSubmit = (values)=>console.log(values, "Values")

function validateEmail(value) {
  // if the field is empty
  if (!value) {
    console.log('2,asm')
    return 'This field is required';
  }
  // if the field is not a valid email
  const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
  if (!regex.test(value)) {
    return 'This field must be a valid email';
  }
  console.log('rlee')
  // All is good
  return true;
}
</script>
