<template>
  <div class="login-page">
    <div class="login-card">
      <header class="login-header">
        <h1 class="login-title">WellRx</h1>
        <p class="login-subtitle">Sign in to your pharmacy account</p>
      </header>

      <Alert
        v-if="errorMessage"
        variant="danger"
        dismissible
        class="mb-5"
        @hidden="clearError"
      >
        {{ errorMessage }}
      </Alert>

      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <FormLabel for="email">Email address</FormLabel>
          <FormInput
            id="email"
            v-model="email"
            type="email"
            placeholder="you@example.com"
            required
            autocomplete="email"
          />
        </div>
        <div class="form-group">
          <FormLabel for="password">Password</FormLabel>
          <FormInput
            id="password"
            v-model="password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="current-password"
          />
        </div>
        <Button
          type="submit"
          variant="primary"
          class="login-submit"
          :disabled="authStore.isPending"
        >
          <span v-if="authStore.isPending">Signing in…</span>
          <span v-else>Sign in</span>
        </Button>
      </form>

      <footer class="login-footer">
        <router-link
          :to="{ name: 'login', params: { identifier: route.params.identifier } }"
          class="login-link"
        >
          Forgot password?
        </router-link>
        <span class="login-sep" aria-hidden="true">·</span>
        <router-link
          :to="{ name: 'signup', params: { identifier: route.params.identifier } }"
          class="login-link"
        >
          Create an account
        </router-link>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
import useAuthStore from '@/stores/auth';
import useTenantStore from '@/stores/tenant';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { FormInput, FormLabel } from '@/components/Base/Form';
import Button from '@/components/Base/Button';
import Alert from '@/components/Base/Alert';

const email = ref('');
const password = ref('');
const errorMessage = ref('');

const tenantStore = useTenantStore();
const authStore = useAuthStore();
const route = useRoute();
const router = useRouter();

/**
 * Clears the error message when the alert is dismissed.
 */
const clearError = (): Record<string, never> => {
  errorMessage.value = '';
  return {};
};

/**
 * Verifies that the tenant (pharmacy) exists via the URL identifier.
 */
const accountVerification = async (): Promise<void> => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

/**
 * Submits the login form, handles errors, and redirects to the dashboard on success.
 */
const handleLogin = async (): Promise<void> => {
  errorMessage.value = '';
  try {
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
  } catch (err) {
    const message =
      err && typeof err === 'object' && 'message' in err
        ? String((err as { message: unknown }).message)
        : 'Invalid credentials. Please try again.';
    errorMessage.value = message;
  }
};

onMounted(async () => {
  await accountVerification();
});
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
  padding: 1.5rem;
}

.login-card {
  width: 100%;
  max-width: 28rem;
  background: #fff;
  border-radius: 1rem;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.15), 0 0 0 1px rgb(0 0 0 / 0.05);
  padding: 2.5rem;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #0f172a;
  letter-spacing: -0.025em;
  margin: 0 0 0.5rem 0;
}

.login-subtitle {
  font-size: 0.9375rem;
  color: #64748b;
  margin: 0;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.login-submit {
  width: 100%;
  margin-top: 0.5rem;
  padding: 0.75rem 1rem;
  font-size: 1rem;
}

.login-footer {
  margin-top: 2rem;
  text-align: center;
  font-size: 0.875rem;
}

.login-link {
  color: #2563eb;
  text-decoration: none;
  font-weight: 500;
}

.login-link:hover {
  text-decoration: underline;
}

.login-sep {
  margin: 0 0.5rem;
  color: #94a3b8;
}
</style>
