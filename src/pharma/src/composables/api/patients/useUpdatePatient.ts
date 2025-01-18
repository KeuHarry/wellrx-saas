import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PatientRequest } from '@/composables/models/PatientRequest';

const tenantStore = useTenantStore();

export default function useUpdatePatient(): (
  id: number,
  payload: PatientRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: PatientRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/patients/${id}`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/patients, can't update patient`);
    }

    return response.data;
  };
}
