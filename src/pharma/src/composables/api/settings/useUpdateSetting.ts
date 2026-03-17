import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Setting } from '@/composables/models/Setting';
import { SettingRequest } from '@/composables/models/SettingRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateSetting(): (
  payload: SettingRequest,
) => Promise<ApiResponse<Setting[]>> {
  return async (payload: SettingRequest) => {
    const response = await axiosTenant.put<ApiResponse<Setting[]>>(`/settings`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/settings, can't update settings`);
    }

    return response.data;
  };
}
