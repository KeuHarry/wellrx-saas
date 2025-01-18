import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SupplierRequest } from '@/composables/models/SupplierRequest';
import { Supplier } from '@/composables/models/Supplier';

const axiosTenant = useSetupAxios();

export default function useCreateSupplier(): (
  payload: SupplierRequest,
) => Promise<ApiResponse<Supplier>> {
  return async (payload: SupplierRequest) => {
    const response = await axiosTenant.post<ApiResponse<Supplier>>(`/suppliers`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't create supplier`);
    }

    return response.data;
  };
}
