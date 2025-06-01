import { useSetupAxios } from '../useAxiosTenant';
import { Category } from '@/composables/models/Category';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetCategories(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Category[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const response = await axiosTenant.get<ApiResponse<Category[]>>(`/categories?${queryParams}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/categories, can't get categories list`);
    }

    return response.data;
  };
}
