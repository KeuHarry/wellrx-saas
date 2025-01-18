import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Setting } from '@/composables/models/Setting';

const axiosTenant = useSetupAxios();

export default function useGetSettings(): () => Promise<ApiResponse<Setting[]>> {
  return async () => {
    const response = await axiosTenant.get<ApiResponse<Setting[]>>(`/settings`);
    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/settings, can't get settings list`);
    }

    return response.data;
  };
}
