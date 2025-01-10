import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteCategory(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/categories/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/shelves, can't delete category`);
    }

    return response.data;
  };
}
