import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteCustomer(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/customers/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/customers, can't delete customer`);
    }

    return response.data;
  };
}
