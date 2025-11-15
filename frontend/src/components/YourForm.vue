<script setup>
import { useForm } from 'vee-validate';
import ValidatedFormControl from './ValidatedFormControl.vue';

const { handleSubmit } = useForm();

const onSubmit = handleSubmit((values) => {
  console.log('Form submitted with values:', values);
});
</script>

<template>
  <form @submit="onSubmit">
    <ValidatedFormControl name="email" type="email" :rules="'required|email'" placeholder="Enter your email" />

    <ValidatedFormControl name="password" type="password" :rules="'required|min:6'" placeholder="Enter your password" />

    <button type="submit">Submit</button>
  </form>
</template>
