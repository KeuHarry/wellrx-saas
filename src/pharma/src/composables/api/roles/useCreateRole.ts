import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { RoleRequest } from '@/composables/models/RoleRequest';
import { Role } from '@/composables/models/Role';

const axiosTenant = useSetupAxios();

export default function useCreateRole(): (payload: RoleRequest) => Promise<ApiResponse<Role>> {
  return async (payload: RoleRequest) => {
    const response = await axiosTenant.post<ApiResponse<Role>>(`/roles`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't create role`);
    }

    return response.data;
  };
}
