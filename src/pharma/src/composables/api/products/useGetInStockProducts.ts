import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { InStockProduct } from '@/composables/models/InStockProduct';

const axiosTenant = useSetupAxios();

export default function useGetInStockProducts(
  searchParams: SearchParams,
): () => Promise<ApiResponse<InStockProduct[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<InStockProduct[]>>(
        `/in-stock?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/in-stock: Failed to fetch the products list.`);
    }
  };
}
