import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';

const tenantStore = useTenantStore();

export default function useUpdatePrescriber(): (
  id: number,
  payload: PrescriberRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: PrescriberRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/prescribers/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't update prescriber`);
    }

    return response.data;
  };
}
