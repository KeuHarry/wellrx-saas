import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Product } from '@/composables/models/Product';

const axiosTenant = useSetupAxios();

export default function useGetProduct(): (id: number) => Promise<ApiResponse<Product>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Product>>(`/products/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/products, can't get product`);
    }

    return response.data;
  };
}
