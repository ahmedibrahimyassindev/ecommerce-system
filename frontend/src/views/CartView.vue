<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { useCartStore } from '../stores/cart'

const cart = useCartStore()
const router = useRouter()
const error = ref('')
const checkout = ref({
  payment_method: 'cash_on_delivery',
  shipping_name: '',
  shipping_phone: '',
  shipping_address: '',
  shipping_city: '',
  shipping_country: 'Egypt',
  notes: '',
})

async function placeOrder() {
  error.value = ''

  try {
    await api.post('/orders', checkout.value)
    cart.clear()
    router.push({ name: 'orders' })
  } catch (exception) {
    error.value = exception.response?.data?.message || 'Unable to place order.'
  }
}

onMounted(cart.fetch)
</script>

<template>
  <section class="page-header">
    <div>
      <p class="eyebrow">Checkout</p>
      <h1>Your cart</h1>
    </div>
    <strong>${{ cart.subtotal.toFixed(2) }}</strong>
  </section>

  <div v-if="error" class="alert alert-danger">{{ error }}</div>
  <div v-if="!cart.items.length" class="empty-state">Your cart is empty.</div>

  <div v-else class="checkout-grid">
    <div class="list-panel">
      <div v-for="item in cart.items" :key="item.id" class="line-item">
        <div>
          <strong>{{ item.product.name }}</strong>
          <p>${{ item.product.price }} each</p>
        </div>
        <input class="form-control quantity-input" type="number" min="1" :value="item.quantity" @change="cart.update(item, Number($event.target.value))">
        <button class="btn btn-sm btn-outline-danger" type="button" @click="cart.remove(item)">Remove</button>
      </div>
    </div>

    <form class="form-stack" @submit.prevent="placeOrder">
      <input v-model="checkout.shipping_name" class="form-control" placeholder="Full name" required>
      <input v-model="checkout.shipping_phone" class="form-control" placeholder="Phone" required>
      <input v-model="checkout.shipping_address" class="form-control" placeholder="Address" required>
      <input v-model="checkout.shipping_city" class="form-control" placeholder="City" required>
      <select v-model="checkout.payment_method" class="form-select">
        <option value="cash_on_delivery">Cash on delivery</option>
        <option value="card">Credit / debit card</option>
        <option value="payment_gateway">Online payment gateway</option>
      </select>
      <textarea v-model="checkout.notes" class="form-control" placeholder="Notes"></textarea>
      <button class="btn btn-dark" type="submit">Place order</button>
    </form>
  </div>
</template>
