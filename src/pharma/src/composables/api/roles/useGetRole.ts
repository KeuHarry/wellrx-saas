import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Role } from '@/composables/models/Role';

const tenantStore = useTenantStore();

export default function useGetRole(): (id: number) => Promise<ApiResponse<Role>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Role>>(`/roles/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't get role`);
    }

    return response.data;
  };
}
