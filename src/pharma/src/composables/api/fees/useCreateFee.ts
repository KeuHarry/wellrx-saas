import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { FeeRequest } from '@/composables/models/FeeRequest';
import { Fee } from '@/composables/models/Fee';

const tenantStore = useTenantStore();

export default function useCreateFee(): (payload: FeeRequest) => Promise<ApiResponse<Fee>> {
  return async (payload: FeeRequest) => {
    const response = await axiosTenant.post<ApiResponse<Fee>>(`/fees`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/fees, can't create fee`);
    }

    return response.data;
  };
}
