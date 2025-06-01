import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Customer } from '@/composables/models/Customer';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetCustomers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Customer[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const response = await axiosTenant.get<ApiResponse<Customer[]>>(`/customers?${queryParams}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/customers, can't get customers list`);
    }

    return response.data;
  };
}
