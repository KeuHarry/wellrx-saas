import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteLabelCode(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/labels/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/labels, can't delete label code`);
    }

    return response.data;
  };
}
