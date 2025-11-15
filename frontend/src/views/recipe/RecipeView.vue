<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Recipes" main>
      </SectionTitleLineWithButton>
      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add Recipe" to="/add-recipe" />
        <TableSampleClients
          :columns="columns"
          :received="data"
          @edit="editRecord"
          @delete="deleteRecord"
          @row-click="handleRecipeClick"
        >
          <template #cell-inventory_items="{ row }">
            <div class="relative group">
              <div class="cursor-pointer text-blue-600 hover:text-blue-800">
                {{ row.original.inventory_items?.length || 0 }} items (Hover to view)
                <div class="hidden group-hover:block absolute left-0 top-full mt-1 bg-white p-2 shadow-lg rounded-md z-50 min-w-[200px]">
                  <div v-for="item in row.original.inventory_items" :key="item.id" class="text-sm py-1">
                    {{ item.item_name }}: {{ item.pivot.quantity }}
                  </div>
                </div>
              </div>
            </div>
          </template>
        </TableSampleClients>
      </CardBox>

      <RecipeModal
        v-model="showModal"
        :recipe="selectedRecipe?.original"
      />
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { ref } from 'vue'
import { mdiTableBorder } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import { useRecipeStore } from '@/stores/recipe'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import RecipeModal from '@/components/RecipeModal.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const recipeStore = useRecipeStore()
const { data } = storeToRefs(recipeStore)
const selectedRecipe = ref(null)
const showModal = ref(false)

onMounted(async () => {
  await recipeStore.getRecipe()
  console.log(data.value)
})

const handleRecipeClick = (recipe) => {
  console.log('Recipe clicked:', recipe)
  selectedRecipe.value = recipe
  showModal.value = true
}

const editRecord = (record) => {
  router.push({ name: "EditRecipe", params: { id: record.id } })
}

const deleteRecord = (record) => {
  console.log('Delete record:', record)
}

const columns = [
  {
    accessorKey: "name",
    header: "Name",
  },
  {
    accessorKey: "product.name",
    header: "Product",
    cell: ({ row }) => {
      const product = row.original.product
      return product ? `${product.name} (${product.type})` : '-'
    }
  },
  {
    accessorKey: "inventory_items",
    header: "Ingredients",
    cell: ({ row }) => {
      const items = row.original.inventory_items || []
      return `${items.length} items`
    }
  },
  {
    accessorKey: "instruction",
    header: "Instruction",
    cell: ({ row }) => {
      const instruction = row.original.instruction
      return instruction?.length > 50 ? instruction.substring(0, 50) + '...' : instruction
    }
  },
  {
    accessorKey: "updated_at",
    header: "Modified",
    cell: ({ row }) => new Date(row.original.updated_at).toLocaleDateString()
  },
]
</script>
