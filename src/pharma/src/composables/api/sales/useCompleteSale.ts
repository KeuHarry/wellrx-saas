import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { Sale } from '@/composables/models/Sale';
import { CompleteSaleRequest } from '@/composables/models/CompleteSaleRequest';

const axiosTenant = useSetupAxios();

export default function useCompleteSale(): (
  payload: CompleteSaleRequest,
) => Promise<ApiResponse<Sale>> {
  return async (payload: CompleteSaleRequest) => {
    const response = await axiosTenant.post<ApiResponse<Sale>>(`/sales/complete`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales/complete, can't complete the sale`);
    }

    return response.data;
  };
}
