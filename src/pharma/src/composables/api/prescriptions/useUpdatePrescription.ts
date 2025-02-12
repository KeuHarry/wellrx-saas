import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PrescriptionRequest } from '@/composables/models/PrescriptionRequest';

const axiosTenant = useSetupAxios();

export default function useUpdatePrescription(): (
  id: number,
  payload: PrescriptionRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: PrescriptionRequest) => {
    const response = await axiosTenant.patch<ApiResponse<number>>(`/prescriptions/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/prescriptions/${id}, can't update prescription`);
    }

    return response.data;
  };
}
