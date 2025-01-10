import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SupplierRequest } from '@/composables/models/SupplierRequest';

const tenantStore = useTenantStore();

export default function useUpdateSupplier(): (
  id: number,
  payload: SupplierRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: SupplierRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/suppliers/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't update supplier`);
    }

    return response.data;
  };
}
