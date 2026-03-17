import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { SaleFragment } from '@/composables/models/SaleFragment';

const axiosTenant = useSetupAxios();

export default function useGetUnPaidSales(
  searchParams: SearchParams,
): () => Promise<ApiResponse<SaleFragment[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<SaleFragment[]>>(
        `/unpaid-sales?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/unpaid-sales: Failed to fetch the sales list.`);
    }
  };
}
