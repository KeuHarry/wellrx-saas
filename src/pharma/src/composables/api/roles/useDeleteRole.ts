import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const axiosTenant = useSetupAxios();

export default function useDeleteRole(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/roles/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't delete role`);
    }

    return response.data;
  };
}
