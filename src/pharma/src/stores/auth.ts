import useLogin from '@/composables/api/authentication/useLogin';
import { AuthUser } from '@/composables/models/AuthUser';
import { Credentials } from '@/types/credentials';
import { defineStore } from 'pinia';
import useSettingStore from './setting';

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
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useAuthStore;
