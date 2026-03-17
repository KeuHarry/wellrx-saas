import { OrderRequest } from '@/composables/models/OrderRequest';
import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { Order } from '@/composables/models/Order';

const axiosTenant = useSetupAxios();

export default function useCreateOrder(): (payload: OrderRequest) => Promise<ApiResponse<Order>> {
  return async (payload: OrderRequest) => {
    const response = await axiosTenant.post<ApiResponse<Order>>(`/orders`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/orders, can't create order`);
    }

    return response.data;
  };
}
