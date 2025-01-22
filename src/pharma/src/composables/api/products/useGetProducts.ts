import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Product } from '@/composables/models/Product';

const axiosTenant = useSetupAxios();

export default function useGetProducts(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Product[]>> {
  
  // Helper to build query parameters
  const buildQueryParams = (params: SearchParams): string => {
    const query = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        query.append(key, value.toString());
      }
    });

    return query.toString();
  };

  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Product[]>>(`/products?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/products: Failed to fetch the products list.`);
    }
  };
}
