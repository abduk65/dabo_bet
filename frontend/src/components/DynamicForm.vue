<template>
  <Form>
    <div v-for="{name, as, label, children, ...attrs} in schema.fields" :key="name" >
      <label for="name">{{label}}</label>
      <Field :as="as" :name="name"   v-bind="attrs" >
        <template v-if="children && children.length" >
          <component v-for="({tag, text, ...childAttrs}, idx) in children" :key="idx" :is="tag" v-bind="childAttrs">
            {{text}}
          </component>
        </template>
      </Field>
      <ErrorMessage :name="name" ></ErrorMessage>
    </div>
    <button>submit</button>
  </Form>
</template>
<script setup >
import { ErrorMessage, Field, Form } from 'vee-validate'
const props = defineProps(['schema'])
const schema = props.schema
</script>
