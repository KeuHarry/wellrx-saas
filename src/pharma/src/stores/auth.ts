import useLogin from '@/composables/api/authentication/useLogin';
import { AuthUser } from '@/composables/models/AuthUser';
import { Credentials } from '@/types/credentials';
import router from '@/router';
import { defineStore } from 'pinia';
import useSettingStore from './setting';
import useTenantStore from './tenant';
import { useSetupAxios } from '@/composables/api/useAxiosTenant';

type TenantState = {
  authUser: AuthUser | null;
  isPending: boolean;
};

export const useAuthStore = defineStore('auth-store', {
  state: (): TenantState => ({
    authUser: null,
    isPending: false,
  }),

  getters: {
    getAuthUser: (state) => state.authUser,
  },

  actions: {
    async loginApi(credentials: Credentials) {
      const login = useLogin();
      try {
        this.isPending = true;
        const response = await login(credentials);
        this.authUser = response.user;
        sessionStorage.setItem('user', JSON.stringify(this.authUser));
        sessionStorage.setItem('authToken', response.token);

        const settingStore = useSettingStore();
        await settingStore.getSettingsApi();
        this.isPending = false;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },

    async logoutApi() {
      const tenantStore = useTenantStore();
      try {
        const axiosTenant = useSetupAxios();
        await axiosTenant.post('/logout');
      } catch {
        // Ignore errors (e.g. 401); we still clear local state
      } finally {
        this.authUser = null;
        sessionStorage.removeItem('authToken');
        sessionStorage.removeItem('user');
        sessionStorage.removeItem('tenantUrl');
        sessionStorage.removeItem('tenantIdentifier');
        const identifier = tenantStore.getTenantIdentifier;
        if (identifier) {
          router.push({ name: 'login', params: { identifier } });
        } else {
          router.push({ path: '/' });
        }
      }
    },
  },
});

export default useAuthStore;
