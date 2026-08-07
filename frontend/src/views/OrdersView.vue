<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

const orders = ref([])

async function loadOrders() {
  const { data } = await api.get('/orders')
  orders.value = data.data
}

onMounted(loadOrders)
</script>

<template>
  <section class="page-header">
    <div>
      <p class="eyebrow">History</p>
      <h1>Orders</h1>
    </div>
  </section>
  <div v-if="!orders.length" class="empty-state">No orders yet.</div>
  <div v-else class="list-panel">
    <article v-for="order in orders" :key="order.id" class="order-row">
      <div>
        <strong>{{ order.order_number }}</strong>
        <p>{{ order.items.length }} items · {{ order.payment_method }}</p>
      </div>
      <span class="badge text-bg-secondary">{{ order.status }}</span>
      <strong>${{ order.total }}</strong>
    </article>
  </div>
</template>
