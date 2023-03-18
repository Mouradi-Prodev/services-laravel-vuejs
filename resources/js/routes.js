// routes.js
import UserDashboard from './components/UserDashboard.vue'

export default [
  {
    path: '/home',
    name: 'home',
    component: UserDashboard,
    meta: {
      requiresAuth: true
    }
  }
]
