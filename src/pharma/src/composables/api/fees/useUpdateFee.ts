import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { FeeRequest } from '@/composables/models/FeeRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateFee(): (
  id: number,
  payload: FeeRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: FeeRequest) => {
    const response = await axiosTenant.patch<ApiResponse<number>>(`/fees/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/fees, can't update fee`);
    }

    return response.data;
  };
}
