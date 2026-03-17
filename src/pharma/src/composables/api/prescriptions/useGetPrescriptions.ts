import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { PrescriptionFragment } from '@/composables/models/PrescriptionFragment';

const axiosTenant = useSetupAxios();

export default function useGetPrescriptions(
  searchParams: SearchParams,
): () => Promise<ApiResponse<PrescriptionFragment[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<PrescriptionFragment[]>>(
        `/prescriptions?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/prescriptions: Failed to fetch the prescriptions list.`,
      );
    }
  };
}
