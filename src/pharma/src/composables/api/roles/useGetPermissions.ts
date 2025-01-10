import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Permission } from '@/composables/models/Permission';

const tenantStore = useTenantStore();

export default function useGetPermissions(): () => Promise<ApiResponse<Permission[]>> {
  return async () => {
    const response = await axiosTenant.get<ApiResponse<Permission[]>>('/permissions');

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/permissions, can't get permissions list`);
    }

    return response.data;
  };
}
