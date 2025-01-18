import useTenantStore from '@/stores/tenant';
import axios, { AxiosInstance } from 'axios';

export let axiosTenant: AxiosInstance | null = null;

export function useSetupAxios(): AxiosInstance {
  if (!axiosTenant) {
    axiosTenant = axios.create();

    axiosTenant.interceptors.request.use(
      (config) => {
        const tenantStore = useTenantStore();
        const tenantUrl = tenantStore.getTenantUrl;

        if (tenantUrl.length > 0) {
          config.baseURL = tenantUrl;
        }

        const token = sessionStorage.getItem('authToken');
        if (token) {
          config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;
      },
      (error) => {
        return Promise.reject(error);
      },
    );
  }

  return axiosTenant;
}

export default useSetupAxios; // Export setup function
