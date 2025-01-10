import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Prescriber } from '@/composables/models/Prescriber';

const tenantStore = useTenantStore();

export default function useGetPrescribers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Prescriber[]>> {
  const urlSearchParams = new URLSearchParams();
  if (searchParams.search) {
    urlSearchParams.append('search', searchParams.search);
  }
  if (searchParams.sortBy) {
    urlSearchParams.append('sortBy', searchParams.sortBy);
  }
  if (searchParams.sortOrder) {
    urlSearchParams.append('sortOrder', searchParams.sortOrder);
  }
  if (searchParams.offset >= 0) {
    urlSearchParams.append('offset', searchParams.offset.toString());
  }
  if (searchParams.limit) {
    urlSearchParams.append('limit', searchParams.limit.toString());
  }

  return async () => {
    const queryParams = urlSearchParams.toString();
    const response = await axiosTenant.get<ApiResponse<Prescriber[]>>(
      `/prescribers?${queryParams}`,
    );

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't get prescribers list`);
    }

    return response.data;
  };
}
