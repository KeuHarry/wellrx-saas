import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { OrderRequest } from '@/composables/models/OrderRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateOrder(): (
  id: number,
  payload: OrderRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: OrderRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/orders/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/orders/${id}, can't update order`);
    }

    return response.data;
  };
}
