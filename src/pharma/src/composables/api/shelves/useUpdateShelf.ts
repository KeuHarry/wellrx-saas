import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { ShelfRequest } from '@/composables/models/ShelfRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateShelf(): (
  id: number,
  payload: ShelfRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: ShelfRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/shelves/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/shelves, can't update shelf`);
    }

    return response.data;
  };
}
