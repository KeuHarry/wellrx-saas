import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { InsurerRequest } from '@/composables/models/InsurerRequest';
import { Insurer } from '@/composables/models/Insurer';

const axiosTenant = useSetupAxios();

export default function useCreateInsurer(): (
  payload: InsurerRequest,
) => Promise<ApiResponse<Insurer>> {
  return async (payload: InsurerRequest) => {
    const response = await axiosTenant.post<ApiResponse<Insurer>>(`/insurers`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/insurers, can't create insurer`);
    }

    return response.data;
  };
}
