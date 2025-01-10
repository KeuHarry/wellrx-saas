import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SupplierRequest } from '@/composables/models/SupplierRequest';
import { Supplier } from '@/composables/models/Supplier';

const tenantStore = useTenantStore();

export default function useCreateSupplier(): (
  payload: SupplierRequest,
) => Promise<ApiResponse<Supplier>> {
  return async (payload: SupplierRequest) => {
    const response = await axiosTenant.post<ApiResponse<Supplier>>(`/suppliers`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't create supplier`);
    }

    return response.data;
  };
}
