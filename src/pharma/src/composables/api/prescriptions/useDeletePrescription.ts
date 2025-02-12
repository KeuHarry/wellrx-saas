import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const axiosTenant = useSetupAxios();

export default function useDeletePrescription(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/prescriptions/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/prescriptions, can't delete prescription`);
    }

    return response.data;
  };
}
