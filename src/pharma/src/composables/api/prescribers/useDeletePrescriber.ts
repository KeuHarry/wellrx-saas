import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeletePrescriber(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/prescribers/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't delete prescriber`);
    }

    return response.data;
  };
}
