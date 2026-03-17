import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Shelf } from '@/composables/models/Shelf';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetShelves(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Shelf[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Shelf[]>>(`/shelves?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/shelves, can't get shelves list`);
    }
  };
}
