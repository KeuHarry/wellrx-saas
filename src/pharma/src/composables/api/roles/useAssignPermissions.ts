import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { AssignPermissionRequest } from '@/composables/models/AssignPermissionRequest';

const axiosTenant = useSetupAxios();

export default function useAssignPermissions(): (
  payload: AssignPermissionRequest,
) => Promise<ApiResponse<boolean>> {
  return async (payload: AssignPermissionRequest) => {
    const response = await axiosTenant.post<ApiResponse<boolean>>(`/assign`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/assign, can't assign permissions`);
    }

    return response.data;
  };
}
