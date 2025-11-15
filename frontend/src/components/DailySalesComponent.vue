<template>
      <div class="p-4">
        <div class="relative">
          <div class="absolute">
            <CardBoxModal v-model="modalOneActive" title="Please confirm action" button-label="Confirm" has-cancel>
              <table>
                <thead>
                <tr>
                  <td>Product</td>
                  <td>Quantity</td>
                  <td>Carryover</td>
                  <td> Commission Table</td>
                </tr>

                </thead>
                <tbody>
                <tr v-for="(sale, index) in dailySales.salesData" :key="sale.id">
                  <td class="w-fit">{{ sale.name }}</td>
                  <td>{{ sale.quantity }}</td>
                  <td>{{ sale.carryover }}</td>
                  <table>
                    <tbody>
                    <tr v-for="(comm, a) in sale.selectedCommission" :key="comm.id">
                      <td>{{ comm.name }}</td>
                      <td>{{ csvValues[index]?.[a] || 'N/A' }}</td>
                    </tr>
                    </tbody>
                  </table>
                </tr>
                </tbody>
              </table>
            </CardBoxModal>
          </div>
        </div>
        <div class="">
          <table>
            <thead>
            <tr>
              <th class="w-1/5">Product</th>
              <th class="w-1/8">Quantity</th>
              <th class="w-1/5">Adari</th>
              <th class="w-1/8" v-if="commissionPresent">Commission Recipients</th>
              <th class="w-1/8" v-if="commissionPresent">Commission Amount</th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="sale in dailySales" :key="sale.id" class="w-full">
              <td>
                <select class="text-black border rounded mr-2 py-1 px-8" name="">
                  <option value="sale.id" class="text-black">{{ sale.name  }} | {{ sale.price }}</option>
                </select>
              </td>
              <td class="w-1/8">
                <input
                  v-model="sale.quantity" type="number" placeholder="Quantity"
                       class="text-black py-1 px-2 border w-20 rounded" />
              </td>
              <td class="w-1/5">
                <input
                  v-model="sale.carryover" type="number" placeholder="Carryover"
                       class="text-black py-1 px-2 border w-20 rounded" />
              </td>
              <td class="w-1/8 relative">
                <TagContainer v-model="sale.selectedCommission" v-if="commissionPresent" :commissions="filteredCommission" />
              </td>
              <td class="w-1/8 z-10">
                <input
                  v-model="sale.commissionQuantityCSV" type="text" placeholder="Commission CSV"
                       class="text-black py-1 px-2 border w-20 rounded" v-if="commissionPresent" />
              </td>
            </tr>
            <tr>
              <td></td>
              <td>{{ main }}</td>
              <td>{{adari}}</td>
              <td>{{}}</td>
              <td></td>
            </tr>
            </tbody>
          </table>
          <BaseButton label="Calculate" @click="calculate" />
        </div>
      </div>
</template>
<script setup>
import { useProductsStore } from '@/stores/products';
import { storeToRefs } from 'pinia';
import { computed, isReactive, onMounted, onUnmounted, ref, unref, watch, watchEffect } from 'vue'
import TagContainer from './pill_tag/TagContainer.vue';
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient';
import BaseButton from '@/components/base/BaseButton.vue'
import { useBranchStore } from '@/stores/branches'

const props = defineProps(
  {
    branchData: {
      type : Object,
      required: true
    }
  }
)
const commissionPresent = computed(()=>filteredCommission.value.length>0)
const branchStore = useBranchStore()
const commissionStore = useCommissionRecipientStore()
const { data: commissions } = storeToRefs(commissionStore)

const modalOneActive = ref(false)
const csvProcessed = () => {
  return dailySales.value.addingAddisFeature =  dailySales.value.map((data) => {
    return data.commission ? data.commission.split(',') : [];
  });
}

const main = ref()
const adari = ref()
const transformedCommission = ref()

let csvValues = ref([])
const pillClick = (id) => console.log(id, "PILLLLLLL")

const calculate = (e) => {
  modalOneActive.value = true
  // csvValues.value = csvProcessed()
  // branchStore.updateSales(dailySales.value)
  console.log(props.branchData, 'BRANCH DATA INSIDE THE CALCULATE FOR BRANCH', branchStore.activeBranch )
}

const displayedComm = computed(()=>transformedCommission.value)

const dailySales = computed(()=>props.branchData.salesData)

let filteredCommission = computed(()=>commissions.value.filter( val => val.branch_id === branchStore.activeBranch.id))

watch(()=>branchStore.activeBranch, (val, oldValue) => {
  // filteredCommission.value = commissions.value.filter( val => val.branch_id === branchStore.activeBranch.id)
  // branchStore.completeSalesData[branchStore.activeBranch.id]
  //   .then(res=> console.log(res, 'THIS IS TECHNICALLY SPEAKING THE DATA STORE FOR THE CURRENT DATA '))
  // console.log(dailySales, props.branchData, isReactive(props.branchData), 'FINAL CHECKER')
})

watchEffect((value, oldValue, onCleanup)=>{
 main.value = dailySales?.value.reduce((acc, val)=> {
    return acc + (val.price * val.quantity)
  } , 0)

  adari.value = dailySales?.value.reduce((acc, val)=>{
    return acc + (val.price * val.carryover)
  }, 0)

  transformedCommission.value = computed(()=>
      dailySales?.value.map((product, index) => {
          if (product.selectedCommission.length > 0) {
            product.selectedCommission.map((branch, i) => {
              // console.log(product.commissionQuantityCSV, '-----========------')

              branch.quantity = product.commissionQuantityCSV?.split(',')[i]
            })
          }
          return product.selectedCommission
      })
  )
})

</script>
<style></style>
