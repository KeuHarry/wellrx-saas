import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { LabelCodeRequest } from '@/composables/models/LabelCodeRequest';

const tenantStore = useTenantStore();

export default function useUpdateLabelCode(): (
  id: number,
  payload: LabelCodeRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: LabelCodeRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/labels/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/labels, can't update label code`);
    }

    return response.data;
  };
}
