import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SupplierRequest } from '@/composables/models/SupplierRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateSupplier(): (
  id: number,
  payload: SupplierRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: SupplierRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/suppliers/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't update supplier`);
    }

    return response.data;
  };
}
