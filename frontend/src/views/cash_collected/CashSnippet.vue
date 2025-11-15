<template>
  <div class="container m-4">
    <form>
      <input class="bg-gray-800" placeholder="Amount" v-model="cashEx" type="text">
      <!--      <BaseButton :id="index" @click="removeCash(index)" class="ml-8" :icon="mdiCloseBoxMultipleOutline" />-->
    </form>
    <div class="text-amber-300 font-bold">
      <!--      {{totalCash}}-->
      {{ parseCash }}
    </div>
    <BaseButton label="Register Cash" @click="addCash"></BaseButton>

  </div>
</template>
<script setup>

import { computed, isReactive, isRef, ref, toRef, toRefs, watch } from 'vue'
import BaseButton from '@/components/base/BaseButton.vue'
import SectionTitle from '@/components/section/SectionTitle.vue'
import { mdiCloseBoxMultipleOutline } from '@mdi/js/commonjs/mdi'
import { useBranchStore } from '@/stores/branches'
import { storeToRefs } from 'pinia'

const props = defineProps({
  branchData: {
    type: Object,
    required: true
  }
})

const parseEx = (spl) => {
  if (spl && spl.startsWith('=')) {
    return spl.split('=')[1].split('+')
  } else if (spl &&  Number.isInteger(spl)) {
    console.log("ezi new yalenew", typeof Number(spl))
    return [spl]
  }
  return []
}

const cashCollected = computed(()=>props.branchData)

const cashEx = ref()
const parseCash = ref()

watch(()=>cashEx.value, (newValue, oldValue)=> {
    console.log(parseEx(cashEx.value), 'parse ex')
    parseCash.value = parseEx(cashEx.value)?.reduce((acc, val)=> acc+=Number(val), 0)
    if(typeof parseCash.value == 'number'  ){
      cashCollected.value.cashCollected = parseCash.value
    }
    console.log(cashCollected.value, "objectu mnylalu")
}, {deep:true})

const randomId = () => Math.floor(Math.random())
// const totalCash = computed(()=>returnedParse.value.reduce((acc, val) => acc += val.amount, 0))

const addCash = () => {
  // cashCollected.value.push({ id: randomId(), amount: 0 })
}

const removeCash = (index) => cashCollected.value.splice(index, 1)
</script>

<style scoped></style>
