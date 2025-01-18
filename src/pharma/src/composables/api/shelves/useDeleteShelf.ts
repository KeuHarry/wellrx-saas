import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const axiosTenant = useSetupAxios();

export default function useDeleteShelf(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/shelves/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/shelves, can't delete shelf`);
    }

    return response.data;
  };
}
