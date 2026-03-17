import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Prescriber } from '@/composables/models/Prescriber';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetPrescribers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Prescriber[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Prescriber[]>>(
        `/prescribers?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't get prescribers list`);
    }
  };
}
