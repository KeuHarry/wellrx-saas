import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { UserRequest } from '@/composables/models/UserRequest';
import { User } from '@/composables/models/User';

const axiosTenant = useSetupAxios();

export default function useCreateUser(): (payload: UserRequest) => Promise<ApiResponse<User>> {
  return async (payload: UserRequest) => {
    const response = await axiosTenant.post<ApiResponse<User>>(`/users`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/users, can't create user`);
    }

    return response.data;
  };
}
