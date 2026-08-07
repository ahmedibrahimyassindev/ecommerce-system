<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const auth = useAuthStore()
const cart = useCartStore()
const router = useRouter()
const products = ref([])
const categories = ref([])
const search = ref('')
const categoryId = ref('')
const loading = ref(false)
const error = ref('')

async function loadProducts() {
  loading.value = true
  error.value = ''

  try {
    const params = { search: search.value || undefined, category_id: categoryId.value || undefined }
    const { data } = await api.get('/products', { params })
    products.value = data.data
  } catch {
    error.value = 'Unable to load products. Make sure the backend server is running.'
  } finally {
    loading.value = false
  }
}

async function loadCategories() {
  const { data } = await api.get('/categories')
  categories.value = data.data
}

async function addToCart(product) {
  if (!auth.user) {
    router.push({ name: 'login' })
    return
  }

  await cart.add(product, 1)
}

onMounted(async () => {
  await Promise.all([loadCategories(), loadProducts()])
  if (auth.user) {
    cart.fetch()
  }
})
</script>

<template>
  <section class="page-header">
    <div>
      <p class="eyebrow">Online store</p>
      <h1>Products</h1>
    </div>
    <div class="filters">
      <input v-model="search" class="form-control" placeholder="Search products" @keyup.enter="loadProducts">
      <select v-model="categoryId" class="form-select" @change="loadProducts">
        <option value="">All categories</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
      </select>
      <button class="btn btn-dark" type="button" @click="loadProducts">Search</button>
    </div>
  </section>

  <div v-if="error" class="alert alert-danger">{{ error }}</div>
  <div v-if="loading" class="text-muted">Loading products...</div>

  <div class="product-grid">
    <article v-for="product in products" :key="product.id" class="product-card">
      <div class="product-image">{{ product.name.slice(0, 1) }}</div>
      <div class="product-body">
        <div class="d-flex justify-content-between gap-3">
          <div>
            <h2>{{ product.name }}</h2>
            <p>{{ product.category?.name }}</p>
          </div>
          <strong>${{ product.price }}</strong>
        </div>
        <p class="description">{{ product.description }}</p>
        <div class="d-flex align-items-center justify-content-between">
          <span class="stock">{{ product.stock }} in stock</span>
          <button class="btn btn-sm btn-dark" type="button" :disabled="!product.stock" @click="addToCart(product)">
            Add to cart
          </button>
        </div>
      </div>
    </article>
  </div>
</template>
