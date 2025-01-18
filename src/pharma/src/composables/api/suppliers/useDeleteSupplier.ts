import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const axiosTenant = useSetupAxios();

export default function useDeleteSupplier(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/suppliers/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't delete supplier`);
    }

    return response.data;
  };
}
