<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-700 to-blue-800"
  >
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <!-- Header -->
      <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-blue-800">WellRx</h1>
        <p class="text-gray-600">Sign in to access your account</p>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Email Field -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
            class="mt-1 block w-full px-4 py-2 placeholder:text-gray-400 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Password Field -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="password"
            type="password"
            id="password"
            placeholder="Enter your password"
            required
            class="mt-1 block w-full px-4 py-2 placeholder:text-gray-400 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300"
          >
            Sign In
          </button>
        </div>
      </form>

      <!-- Footer Links -->
      <div class="mt-6 text-center">
        <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Forgot Password ?</a>
        <span class="mx-2 text-gray-400">|</span>
        <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Create an Account</a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import useAuthStore from '@/stores/auth';
import useTenantStore from '@/stores/tenant';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const tenantStore = useTenantStore();
const authStore = useAuthStore();
const route = useRoute();
const router = useRouter();

//METHODS
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const handleLogin = async () => {
  await authStore.loginApi({
    email: email.value,
    password: password.value,
  });

  if (authStore.getAuthUser?.email) {
    router.push({
      name: 'dashboard-overview-1',
      params: { identifier: route.params.identifier },
    });
  }
};

onMounted(async () => {
  await accountVerification();
});
</script>
