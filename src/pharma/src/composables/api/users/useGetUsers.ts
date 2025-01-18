import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { User } from '@/composables/models/User';

const axiosTenant = useSetupAxios();
const tenantStore = useTenantStore();

export default function useGetUsers(
  searchParams: SearchParams,
): () => Promise<ApiResponse<User[]>> {
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
    const response = await axiosTenant.get<ApiResponse<User[]>>(`/users?${queryParams}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/users, can't get users list`);
    }

    return response.data;
  };
}
