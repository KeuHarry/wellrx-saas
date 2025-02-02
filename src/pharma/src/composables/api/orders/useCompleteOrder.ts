import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { Order } from '@/composables/models/Order';
import { CompleteOrderRequest } from '@/composables/models/CompleteOrderRequest';

const axiosTenant = useSetupAxios();

export default function useCompleteOrder(): (
  payload: CompleteOrderRequest,
) => Promise<ApiResponse<Order>> {
  return async (payload: CompleteOrderRequest) => {
    const response = await axiosTenant.post<ApiResponse<Order>>(`/orders/complete`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/orders/complete, can't complete the order`);
    }

    return response.data;
  };
}
