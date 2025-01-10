import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteSupplier(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/suppliers/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't delete supplier`);
    }

    return response.data;
  };
}
