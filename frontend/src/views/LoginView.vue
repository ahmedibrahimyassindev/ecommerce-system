<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const mode = ref('login')
const error = ref('')
const form = ref({
  name: '',
  email: 'customer@example.com',
  password: 'password',
  password_confirmation: 'password',
})

async function submit() {
  error.value = ''

  try {
    if (mode.value === 'login') {
      await auth.login({ email: form.value.email, password: form.value.password })
    } else {
      await auth.register(form.value)
    }

    router.push({ name: 'shop' })
  } catch (exception) {
    error.value = exception.response?.data?.message || 'Authentication failed.'
  }
}
</script>

<template>
  <section class="auth-panel">
    <div>
      <p class="eyebrow">Account</p>
      <h1>{{ mode === 'login' ? 'Login' : 'Register' }}</h1>
      <p class="text-muted">Seeded users: customer@example.com or admin@example.com, password: password.</p>
    </div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <form class="form-stack" @submit.prevent="submit">
      <input v-if="mode === 'register'" v-model="form.name" class="form-control" placeholder="Name">
      <input v-model="form.email" class="form-control" type="email" placeholder="Email">
      <input v-model="form.password" class="form-control" type="password" placeholder="Password">
      <input v-if="mode === 'register'" v-model="form.password_confirmation" class="form-control" type="password" placeholder="Confirm password">
      <button class="btn btn-dark" type="submit">{{ mode === 'login' ? 'Login' : 'Create account' }}</button>
      <button class="btn btn-link p-0 align-self-start" type="button" @click="mode = mode === 'login' ? 'register' : 'login'">
        {{ mode === 'login' ? 'Create a new account' : 'Use existing account' }}
      </button>
    </form>
  </section>
</template>
