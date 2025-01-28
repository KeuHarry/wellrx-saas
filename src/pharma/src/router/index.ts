import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Landing from '@/views/Landing.vue';

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
          path: '/:identifier/users',
          name: 'user-list',
          component: () => import('../views/UserList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/roles',
          name: 'role-list',
          component: () => import('../views/RoleList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/settings',
          name: 'setting',
          component: () => import('../views/Setting.vue'),
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
        {
          path: '/:identifier/fees',
          name: 'fee-list',
          component: () => import('../views/FeeList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/products',
          name: 'product-list',
          component: () => import('../views/ProductList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/products/add',
          name: 'add-product',
          component: () => import('../views/AddProduct.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/products/edit/:id',
          name: 'edit-product',
          component: () => import('../views/AddProduct.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/customers',
          name: 'customer-list',
          component: () => import('../views/CustomerList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/prescribers',
          name: 'prescriber-list',
          component: () => import('../views/PrescriberList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/patients',
          name: 'patient-list',
          component: () => import('../views/PatientList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/intermediaries',
          name: 'intermediary-list',
          component: () => import('../views/IntermediaryList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/insurers',
          name: 'insurer-list',
          component: () => import('../views/InsurerList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/inventory',
          name: 'inventory',
          component: () => import('../views/Inventory.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/inventory/:type',
          name: 'add-remove-inventory',
          component: () => import('../views/AddRemoveInventory.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/suppliers',
          name: 'supplier-list',
          component: () => import('../views/SupplierList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/labels',
          name: 'labelcode-list',
          component: () => import('../views/LabelCodeList.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: '/:identifier/cash-register',
          name: 'cash-register',
          component: () => import('../views/CashRegister.vue'),
          meta: { requiresAuth: true },
        },
      ],
    },
  ],
});

export default router;
