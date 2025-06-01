import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Intermediary } from '@/composables/models/Intermediary';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetIntermediaries(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Intermediary[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const response = await axiosTenant.get<ApiResponse<Intermediary[]>>(
      `/intermediaries?${queryParams}`,
    );

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/intermediaries, can't get intermediaries list`);
    }

    return response.data;
  };
}
