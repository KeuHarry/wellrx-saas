import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SaleRequest } from '@/composables/models/SaleRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateSale(): (
  id: number,
  payload: SaleRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: SaleRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/sales/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales/${id}, can't update sale`);
    }

    return response.data;
  };
}
