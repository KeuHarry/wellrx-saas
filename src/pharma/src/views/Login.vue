<template>
  <div class="flex min-h-screen flex-col justify-center items-center px-6 py-12 lg:px-8 bg-gray-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h1 class="text-2xl text-center text-primary font-bold">WellRx</h1>
      <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">
        Sign in to your account
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="#" method="POST">
        <div>
          <label for="email" class="block text-sm/6 text-gray-900">Email address</label>
          <div class="mt-2">
            <input
              v-model="email"
              type="email"
              name="email"
              id="email"
              autocomplete="email"
              required
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm/6 text-gray-900">Password</label>
            <div class="text-sm">
              <a href="#" class="font-semibold text-blue-600 hover:text-blue-500"
                >Forgot password ?</a
              >
            </div>
          </div>
          <div class="mt-2">
            <input
              v-model="password"
              type="password"
              name="password"
              id="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <button
            @click="login"
            type="button"
            class="flex w-full justify-center rounded-md bg-primary-blue px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-secondary-blue focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Sign in
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Not yet subscribe ?
        <a href="#" class="font-semibold text-blue-600 hover:text-blue-500"
          >Choose a best plan for your business</a
        >
      </p>
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

const login = async () => {
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
