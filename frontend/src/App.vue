<script setup>
import { computed } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'

const auth = useAuthStore()
const cart = useCartStore()
const router = useRouter()

const cartCount = computed(() => cart.items.reduce((total, item) => total + item.quantity, 0))

async function logout() {
  await auth.logout()
  cart.clear()
  router.push({ name: 'shop' })
}
</script>

<template>
  <div class="app-shell">
    <nav class="navbar navbar-expand-lg border-bottom bg-white sticky-top">
      <div class="container">
        <RouterLink class="navbar-brand fw-bold" :to="{ name: 'shop' }">E-Commerce</RouterLink>
        <div class="d-flex align-items-center gap-2 ms-auto">
          <RouterLink class="btn btn-sm btn-outline-dark" :to="{ name: 'shop' }">Shop</RouterLink>
          <RouterLink class="btn btn-sm btn-outline-dark" :to="{ name: 'cart' }">
            Cart <span class="badge text-bg-dark">{{ cartCount }}</span>
          </RouterLink>
          <RouterLink v-if="auth.user" class="btn btn-sm btn-outline-dark" :to="{ name: 'orders' }">Orders</RouterLink>
          <RouterLink v-if="auth.user?.is_admin" class="btn btn-sm btn-outline-dark" :to="{ name: 'admin' }">Admin</RouterLink>
          <RouterLink v-if="!auth.user" class="btn btn-sm btn-dark" :to="{ name: 'login' }">Login</RouterLink>
          <button v-else class="btn btn-sm btn-dark" type="button" @click="logout">Logout</button>
        </div>
      </div>
    </nav>
    <main class="container py-4">
      <RouterView />
    </main>
  </div>
</template>
