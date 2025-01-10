import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';
import { Prescriber } from '@/composables/models/Prescriber';

const tenantStore = useTenantStore();

export default function useCreatePrescriber(): (
  payload: PrescriberRequest,
) => Promise<ApiResponse<Prescriber>> {
  return async (payload: PrescriberRequest) => {
    const response = await axiosTenant.post<ApiResponse<Prescriber>>(`/prescribers`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't create prescriber`);
    }

    return response.data;
  };
}
