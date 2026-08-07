import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import AdminView from '../views/AdminView.vue'
import CartView from '../views/CartView.vue'
import LoginView from '../views/LoginView.vue'
import OrdersView from '../views/OrdersView.vue'
import ShopView from '../views/ShopView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'shop', component: ShopView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/cart', name: 'cart', component: CartView, meta: { auth: true } },
    { path: '/orders', name: 'orders', component: OrdersView, meta: { auth: true } },
    { path: '/admin', name: 'admin', component: AdminView, meta: { auth: true, admin: true } },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.auth && !auth.user) {
    return { name: 'login' }
  }

  if (to.meta.admin && !auth.user?.is_admin) {
    return { name: 'shop' }
  }
})

export default router
