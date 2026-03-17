import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { Inventory } from '@/composables/models/Inventory';

const axiosTenant = useSetupAxios();

export default function useGetInventoryList(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Inventory[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Inventory[]>>(
        `/inventories?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/inventories: Failed to fetch the inventory list.`,
      );
    }
  };
}
