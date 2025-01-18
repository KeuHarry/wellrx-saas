import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeletePatient(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/patients/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/patients, can't delete patient`);
    }

    return response.data;
  };
}
