<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

const products = ref([])
const categories = ref([])
const form = ref({ category_id: '', name: '', price: 0, stock: 0, description: '' })

async function load() {
  const [productResponse, categoryResponse] = await Promise.all([
    api.get('/products'),
    api.get('/categories'),
  ])
  products.value = productResponse.data.data
  categories.value = categoryResponse.data.data
  form.value.category_id ||= categories.value[0]?.id || ''
}

async function saveProduct() {
  await api.post('/products', form.value)
  form.value = { category_id: categories.value[0]?.id || '', name: '', price: 0, stock: 0, description: '' }
  await load()
}

async function deleteProduct(product) {
  await api.delete(`/products/${product.id}`)
  await load()
}

onMounted(load)
</script>

<template>
  <section class="page-header">
    <div>
      <p class="eyebrow">Admin</p>
      <h1>Inventory</h1>
    </div>
  </section>

  <div class="admin-grid">
    <form class="form-stack" @submit.prevent="saveProduct">
      <select v-model="form.category_id" class="form-select" required>
        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
      </select>
      <input v-model="form.name" class="form-control" placeholder="Product name" required>
      <input v-model.number="form.price" class="form-control" type="number" step="0.01" min="0" placeholder="Price" required>
      <input v-model.number="form.stock" class="form-control" type="number" min="0" placeholder="Stock" required>
      <textarea v-model="form.description" class="form-control" placeholder="Description"></textarea>
      <button class="btn btn-dark" type="submit">Create product</button>
    </form>

    <div class="list-panel">
      <div v-for="product in products" :key="product.id" class="line-item">
        <div>
          <strong>{{ product.name }}</strong>
          <p>{{ product.category?.name }} · ${{ product.price }} · {{ product.stock }} stock</p>
        </div>
        <button class="btn btn-sm btn-outline-danger" type="button" @click="deleteProduct(product)">Delete</button>
      </div>
    </div>
  </div>
</template>
