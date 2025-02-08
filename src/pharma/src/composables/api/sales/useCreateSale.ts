import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { SaleRequest } from '@/composables/models/SaleRequest';
import { Sale } from '@/composables/models/Sale';

const axiosTenant = useSetupAxios();

export default function useCreateSale(): (payload: SaleRequest) => Promise<ApiResponse<Sale>> {
  return async (payload: SaleRequest) => {
    const response = await axiosTenant.post<ApiResponse<Sale>>(`/sales`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales, can't create sale`);
    }

    return response.data;
  };
}
