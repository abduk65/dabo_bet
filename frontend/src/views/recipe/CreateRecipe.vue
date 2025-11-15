<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiPlus" title="Create Recipe" main>
      </SectionTitleLineWithButton>

      <CardBox class="mb-6">
        <form @submit.prevent="handleSubmit">
          <!-- Recipe Name -->
          <FormField label="Recipe Name" help="Enter the name of the recipe">
            <FormControl v-model="form.name" type="text" required />
          </FormField>

          <!-- Product Selection -->
          <FormField label="Product" help="Select the product for this recipe">
            <select v-model="form.product_id"
              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
              <option value="">Select a product</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.name }} ({{ product.type }})
              </option>
            </select>
          </FormField>

          <!-- Instructions -->
          <FormField label="Instructions" help="Enter detailed recipe instructions">
            <FormControl v-model="form.instruction" type="textarea" required />
          </FormField>

          <!-- Ingredients -->
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <label class="font-bold">Ingredients</label>
              <BaseButton type="button" color="info" label="Add Ingredient" @click="addIngredient" />
            </div>

            <div v-for="(ingredient, index) in form.ingredients" :key="index" class="mb-4 p-4 bg-gray-800 rounded-lg">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Item Selection -->
                <FormField label="Item">
                  <select v-model="ingredient.inventory_item_id"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
                    <option value="">Select an item</option>
                    <option v-for="item in inventoryItems" :key="item.id" :value="item.id">
                      {{ item.name }} (Batch #{{ item.batch_number }})
                    </option>
                  </select>
                </FormField>

                <!-- Quantity -->
                <FormField label="Quantity">
                  <FormControl v-model="ingredient.quantity" type="number" min="0" step="0.01" required />
                </FormField>

                <!-- Unit -->
                <FormField label="Unit">
                  <select v-model="ingredient.unit_id"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
                    <option value="">Select a unit</option>
                    <option v-for="unit in units" :key="unit.id" :value="unit.id">
                      {{ unit.name }}
                    </option>
                  </select>
                </FormField>

                <!-- Remove Button -->
                <div class="flex items-end">
                  <BaseButton type="button" color="danger" label="Remove" @click="removeIngredient(index)" />
                </div>
              </div>
            </div>
          </div>

          <BaseButtons>
            <BaseButton type="submit" color="info" label="Create Recipe" :loading="loading" />
            <BaseButton type="button" color="danger" label="Cancel" @click="router.back()" />
          </BaseButtons>
        </form>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { mdiPlus } from '@mdi/js'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import FormField from '@/components/form/FormField.vue'
import FormControl from '@/components/form/FormControl.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseButtons from '@/components/base/BaseButtons.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import { useRecipeStore } from '@/stores/recipe'

const router = useRouter()
const recipeStore = useRecipeStore()
const loading = ref(false)

const products = ref([])
const inventoryItems = ref([])
const units = ref([])

const form = ref({
  name: '',
  product_id: '',
  instruction: '',
  ingredients: []
})

const addIngredient = () => {
  form.value.ingredients.push({
    inventory_item_id: '',
    quantity: '',
    unit_id: ''
  })
}

const removeIngredient = (index) => {
  form.value.ingredients.splice(index, 1)
}

const handleSubmit = async () => {
  try {
    loading.value = true
    await recipeStore.createRecipe(form.value)
    router.push('/recipes')
  } catch (error) {
    console.error('Error creating recipe:', error)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    // Load products, inventory items, and units
    const [productsData, itemsData, unitsData] = await Promise.all([
      recipeStore.getProducts(),
      recipeStore.getInventoryItems(),
      recipeStore.getUnits()
    ])

    products.value = productsData
    inventoryItems.value = itemsData
    units.value = unitsData
  } catch (error) {
    console.error('Error loading data:', error)
  }
})
</script>
