import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { IntermediaryRequest } from '@/composables/models/IntermediaryRequest';

const tenantStore = useTenantStore();

export default function useUpdateIntermediary(): (
  id: number,
  payload: IntermediaryRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: IntermediaryRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/intermediaries/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/intermediaries, can't update intermediary`);
    }

    return response.data;
  };
}
