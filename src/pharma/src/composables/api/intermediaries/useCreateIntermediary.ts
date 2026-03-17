import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Intermediary } from '@/composables/models/Intermediary';
import { IntermediaryRequest } from '@/composables/models/IntermediaryRequest';

const axiosTenant = useSetupAxios();

export default function useCreateIntermediary(): (
  payload: IntermediaryRequest,
) => Promise<ApiResponse<Intermediary>> {
  return async (payload: IntermediaryRequest) => {
    const response = await axiosTenant.post<ApiResponse<Intermediary>>(`/intermediaries`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/intermediaries, can't create intermediary`);
    }

    return response.data;
  };
}
