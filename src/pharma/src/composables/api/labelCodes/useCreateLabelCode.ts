import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { LabelCode } from '@/composables/models/LabelCode';
import { LabelCodeRequest } from '@/composables/models/LabelCodeRequest';

const tenantStore = useTenantStore();

export default function useCreateLabelCode(): (
  payload: LabelCodeRequest,
) => Promise<ApiResponse<LabelCode>> {
  return async (payload: LabelCodeRequest) => {
    const response = await axiosTenant.post<ApiResponse<LabelCode>>(`/labels`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/labels, can't create label code`);
    }

    return response.data;
  };
}
