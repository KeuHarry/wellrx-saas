import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Landing from '@/views/Landing.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'landing',
      component: Landing,
      // children: [
      //   {
      //     path: "/",
      //     name: "dashboard-overview-1",
      //     component: () => import("../views/DashboardOverview1.vue"),
      //   },
      // ]
    },
    {
      path: '/sign-up',
      name: 'signup',
      component: () => import('../views/SignUp.vue'),
    },
    {
      path: '/verification',
      name: 'verification',
      component: () => import('../views/Verification.vue'),
    },
    {
      path: '/:identifier/pricing',
      name: 'pricing',
      component: () => import('../views/Pricing.vue'),
    },
    {
      path: '/:identifier/login',
      name: 'login',
      component: () => import('../views/Login.vue'),
    },
    {
      path: '/:identifier/dashboard',
      name: 'home',
      component: () => import('../views/Home.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: '/:identifier/dashboard',
          name: 'dashboard-overview-1',
          component: () => import('../views/DashboardOverview1.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/categories',
          name: 'category-list',
          component: () => import('../views/CategoryList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/shelves',
          name: 'shelf-list',
          component: () => import('../views/ShelfList.vue'),
          meta: { requiresAuth: true },
        },
      ],
    },
  ],
})

export default router
