import { defineStore } from 'pinia'
import api from '../services/api'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
  }),
  getters: {
    subtotal: (state) => state.items.reduce((total, item) => total + Number(item.line_total || 0), 0),
  },
  actions: {
    clear() {
      this.items = []
    },
    async fetch() {
      const { data } = await api.get('/cart')
      this.items = data.data
    },
    async add(product, quantity = 1) {
      await api.post('/cart', { product_id: product.id, quantity })
      await this.fetch()
    },
    async update(item, quantity) {
      await api.put(`/cart/${item.id}`, { quantity })
      await this.fetch()
    },
    async remove(item) {
      await api.delete(`/cart/${item.id}`)
      await this.fetch()
    },
  },
})
