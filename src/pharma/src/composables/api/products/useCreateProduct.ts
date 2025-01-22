import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Product } from '@/composables/models/Product';
import { ProductRequest } from '@/composables/models/ProductRequest';
import useTenantStore from '@/stores/tenant';

const axiosTenant = useSetupAxios();

export default function useCreateProduct(): (
  payload: ProductRequest,
) => Promise<ApiResponse<Product>> {
  return async (payload: ProductRequest) => {
    const response = await axiosTenant.post<ApiResponse<Product>>(`/products`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/products, can't create product`);
    }

    return response.data;
  };
}
