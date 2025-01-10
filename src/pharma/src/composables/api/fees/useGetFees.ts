import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Fee } from '@/composables/models/Fee';

const tenantStore = useTenantStore();

export default function useGetFees(searchParams: SearchParams): () => Promise<ApiResponse<Fee[]>> {
  const urlSearchParams = new URLSearchParams();
  if (searchParams.search) {
    urlSearchParams.append('search', searchParams.search);
  }
  if (searchParams.tag) {
    urlSearchParams.append('tag', searchParams.tag.toString());
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
    const response = await axiosTenant.get<ApiResponse<Fee[]>>(`/fees?${queryParams}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/fees, can't get fees list`);
    }

    return response.data;
  };
}
