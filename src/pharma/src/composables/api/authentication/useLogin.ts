import { Credentials } from '@/types/credentials';
import useTenantStore from '@/stores/tenant';
import { useSetupAxios } from '../useAxiosTenant';
import { AuthResponse } from '@/composables/models/AuthResponse';

const axiosTenant = useSetupAxios();

export default function useLogin(): (payload: Credentials) => Promise<AuthResponse> {
  return async (payload: Credentials) => {
    const response = await axiosTenant.post<AuthResponse>('/login', payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(
        `${tenantStore.getTenantUrl}/login, can't login. Please verify your credentials`,
      );
    }

    return response.data;
  };
}
