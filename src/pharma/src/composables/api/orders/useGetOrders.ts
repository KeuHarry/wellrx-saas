import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { Order } from '@/composables/models/Order';

const axiosTenant = useSetupAxios();

export default function useGetOrders(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Order[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Order[]>>(`/orders?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/orders: Failed to fetch the orders list.`);
    }
  };
}
