import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { ProductRequest } from '@/composables/models/ProductRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateProduct(): (
  id: number,
  payload: ProductRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: ProductRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/products/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/products/${id}, can't update product`);
    }

    return response.data;
  };
}
