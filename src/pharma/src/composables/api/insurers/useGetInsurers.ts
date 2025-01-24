import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Insurer } from '@/composables/models/Insurer';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetInsurers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Insurer[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Insurer[]>>(`/insurers?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/insurers: Failed to fetch the insurers list.`);
    }
  };
}
