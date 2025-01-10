import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteRole(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/roles/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't delete role`);
    }

    return response.data;
  };
}
