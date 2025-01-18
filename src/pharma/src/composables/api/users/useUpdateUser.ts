import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { UserRequest } from '@/composables/models/UserRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateUser(): (
  id: number,
  payload: UserRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: UserRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/users/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/users, can't update user`);
    }

    return response.data;
  };
}
