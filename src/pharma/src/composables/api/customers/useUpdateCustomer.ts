import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CustomerRequest } from '@/composables/models/CustomerRequest';

const tenantStore = useTenantStore();

export default function useUpdateCustomer(): (
  id: number,
  payload: CustomerRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: CustomerRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/customers/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/customers, can't update customer`);
    }

    return response.data;
  };
}
