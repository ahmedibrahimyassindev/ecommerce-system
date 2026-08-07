import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('auth_user') || 'null'),
    token: localStorage.getItem('auth_token'),
  }),
  actions: {
    setSession(payload) {
      this.user = payload.user
      this.token = payload.token
      localStorage.setItem('auth_user', JSON.stringify(payload.user))
      localStorage.setItem('auth_token', payload.token)
    },
    async login(credentials) {
      const { data } = await api.post('/login', credentials)
      this.setSession(data)
    },
    async register(form) {
      const { data } = await api.post('/register', form)
      this.setSession(data)
    },
    async logout() {
      if (this.token) {
        await api.post('/logout').catch(() => {})
      }

      this.user = null
      this.token = null
      localStorage.removeItem('auth_user')
      localStorage.removeItem('auth_token')
    },
  },
})
