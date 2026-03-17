import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CustomerRequest } from '@/composables/models/CustomerRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateCustomer(): (
  id: number,
  payload: CustomerRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: CustomerRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/customers/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/customers, can't update customer`);
    }

    return response.data;
  };
}
