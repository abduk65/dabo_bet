<template>
  <div v-for="pill in modelValue" :key="pill.id" class="actively-selected" @click="bubbleClick">
    <div class="flex relative">
      <PillTag :label="pill.name" :color="'red'" @click="singleClicked(pill)"></PillTag>
      <div class="close absolute top--4 right--5 cursor-pointer" @click="removeTag(pill)">x</div>
    </div>
  </div>
  <div @click="toggleVisibility" >
    <span class="cursor-pointer">click to toggle</span>
    <div class="absolute top--12 bottom-0 rounded flex flex-wrap bg-gray-800 bg-opacity-500 text-amber-300 w-120 h-full"
         v-if="boxShown" >
      <div v-for="item in defaultPillState" :key="item.id" class="flex-grow flex-shrink basis-auto py-1">
        <PillTag :label="item.name" :color="'red'" @click="pushToSelected(item)"></PillTag>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, watchEffect, isRef, isReactive, toRef } from 'vue'
import PillTag from './PillTag.vue';

const props = defineProps(
  {
    commissions: {
      type: Array,
      required: true
    },
    // boxShown: {
    //   type: Boolean,
    //   required: true
    // },
    modelValue: {
      type: Object, required: true
    }
  },
)

const boxShown = ref(true)
const toggleVisibility = () => {
  boxShown.value = !boxShown.value
}

const selectedCommission = ref([])
const emit = defineEmits(['update:modelValue', 'bubbleClick'])

const removeTag = (item) => {
  selectedCommission.value.splice(selectedCommission.value.indexOf(item), 1);
  emit('update:modelValue', selectedCommission.value)
}

const pushToSelected = (item) => {
  selectedCommission.value.push(item)
  emit('update:modelValue', selectedCommission.value)
}

const bubbleClick = () => {
  emit('bubbleClick')
}

const dynamicClass = computed(() => ({
  'hidden': boxShown,
}));

// const boxShown = ref(false)



const singleClicked = (e) => console.log("Single Pill Clicked")

const defaultPillState = computed(() => props.commissions)
// const selectedCommission = props.selectedCommission
</script>

<style scoped>

.close {
  border: red solid 4px;
  border-radius: 50%;
  height: 100%;
  padding: 0;
  margin: 0;
}

.shown {
  display: block; /* or any other value that suits your needs */
}

.hidden {
  display: none;
}
</style>
