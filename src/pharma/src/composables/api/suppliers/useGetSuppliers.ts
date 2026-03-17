import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Supplier } from '@/composables/models/Supplier';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetSuppliers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Supplier[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Supplier[]>>(`/suppliers?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/suppliers, can't get suppliers list`);
    }
  };
}
