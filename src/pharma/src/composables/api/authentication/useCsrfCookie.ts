//import axios from 'axios'
import useTenantStore from '@/stores/tenant';
import axiosTenant from '../useAxiosTenant';

const tenantStore = useTenantStore();

export default function useCsrfCookie(): () => Promise<any> {
  return async () => {
    const response = await axiosTenant.get<any>('/sanctum/csrf-cookie');

    if (!response) {
      throw new Error(
        `${tenantStore.getTenantUrl}/sanctum/csrf-cookie, can't process: /sanctum/csrf-cookie`,
      );
    }

    return response.data;
  };
}
