import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { IntermediaryRequest } from '@/composables/models/IntermediaryRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateIntermediary(): (
  id: number,
  payload: IntermediaryRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: IntermediaryRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/intermediaries/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/intermediaries, can't update intermediary`);
    }

    return response.data;
  };
}
