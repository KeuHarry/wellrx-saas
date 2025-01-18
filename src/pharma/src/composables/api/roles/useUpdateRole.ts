import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { RoleRequest } from '@/composables/models/RoleRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateRole(): (
  id: number,
  payload: RoleRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: RoleRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/roles/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't update role`);
    }

    return response.data;
  };
}
