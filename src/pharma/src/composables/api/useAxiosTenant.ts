import useTenantStore from '@/stores/tenant';
import axios from 'axios';

const tenantStore = useTenantStore();

const axiosTenant = axios.create();

axiosTenant.interceptors.request.use(
  (config) => {
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
    // Handle request error
    return Promise.reject(error);
  },
);

export default axiosTenant;
