import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { RoleRequest } from '@/composables/models/RoleRequest';

const tenantStore = useTenantStore();

export default function useUpdateRole(): (
  id: number,
  payload: RoleRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: RoleRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/roles/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't update role`);
    }

    return response.data;
  };
}
