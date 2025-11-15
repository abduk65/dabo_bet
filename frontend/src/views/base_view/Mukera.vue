<template>
  <div class="matrix-container">
    <table class="matrix-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Carryover Count</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in products" :key="index">
          <td>{{ product.name }}</td>
          <td>
            <input type="number" v-model="product.quantity" @input="updateCarryover(index)" class="quantity-input" />
          </td>
          <td>
            <input type="number" v-model="product.carryover" class="carryover-input" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [
        { name: 'Product A', quantity: 0, carryover: 0 },
        { name: 'Product B', quantity: 0, carryover: 0 },
        { name: 'Product C', quantity: 0, carryover: 0 }
      ]
    };
  },
  methods: {
    updateCarryover(index) {
      // Example logic to update carryover based on quantity
      // You can customize this logic as per your requirement
      this.products[index].carryover = this.products[index].quantity;
    }
  }
};
</script>

<style scoped>
.matrix-container {
  margin: 20px;
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.matrix-table {
  width: 100%;
  border-collapse: collapse;
  font-family: Arial, sans-serif;
}

.matrix-table th {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  text-align: left;
  border-bottom: 2px solid #4CAF50;
}

.matrix-table td {
  padding: 12px;
  border-bottom: 1px solid #ddd;
}

.matrix-table tr:hover {
  background-color: #f1f1f1;
}

.quantity-input,
.carryover-input {
  width: 100%;
  padding: 8px;
  margin: 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.quantity-input:focus,
.carryover-input:focus {
  border-color: #4CAF50;
  outline: none;
}
</style>
