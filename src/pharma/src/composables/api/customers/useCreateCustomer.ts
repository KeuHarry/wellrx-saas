import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CustomerRequest } from '@/composables/models/CustomerRequest';
import { Customer } from '@/composables/models/Customer';

const axiosTenant = useSetupAxios();

export default function useCreateCustomer(): (
  payload: CustomerRequest,
) => Promise<ApiResponse<Customer>> {
  return async (payload: CustomerRequest) => {
    const response = await axiosTenant.post<ApiResponse<Customer>>(`/customers`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/customers, can't create customer`);
    }

    return response.data;
  };
}
