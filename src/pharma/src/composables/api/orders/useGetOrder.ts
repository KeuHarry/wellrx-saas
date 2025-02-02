import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Order } from '@/composables/models/Order';

const axiosTenant = useSetupAxios();

export default function useGetOrder(): (id: number) => Promise<ApiResponse<Order>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Order>>(`/orders/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/orders, can't get order`);
    }

    return response.data;
  };
}
