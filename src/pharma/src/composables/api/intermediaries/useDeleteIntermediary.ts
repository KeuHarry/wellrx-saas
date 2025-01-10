import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteIntermediary(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/intermediaries/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/intermediaries, can't delete intermediary`);
    }

    return response.data;
  };
}
