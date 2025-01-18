import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Role } from '@/composables/models/Role';

const axiosTenant = useSetupAxios();

export default function useGetRole(): (id: number) => Promise<ApiResponse<Role>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Role>>(`/roles/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/roles, can't get role`);
    }

    return response.data;
  };
}
