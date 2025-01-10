import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { RoleRequest } from '@/composables/models/RoleRequest';
import { Role } from '@/composables/models/Role';

const tenantStore = useTenantStore();

export default function useCreateRole(): (payload: RoleRequest) => Promise<ApiResponse<Role>> {
  return async (payload: RoleRequest) => {
    const response = await axiosTenant.post<ApiResponse<Role>>(`/roles`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't create role`);
    }

    return response.data;
  };
}
