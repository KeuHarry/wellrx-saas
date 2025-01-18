import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CategoryRequest } from '@/composables/models/CategoryRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateCategory(): (
  id: number,
  payload: CategoryRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: CategoryRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/categories/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/categories, can't update category`);
    }

    return response.data;
  };
}
