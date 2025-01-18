import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Patient } from '@/composables/models/Patient';
import { PatientRequest } from '@/composables/models/PatientRequest';

const tenantStore = useTenantStore();

export default function useCreatePatient(): (
  payload: PatientRequest,
) => Promise<ApiResponse<Patient>> {
  return async (payload: PatientRequest) => {
    const response = await axiosTenant.post<ApiResponse<Patient>>(`/patients`, payload);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/patients, can't create patient`);
    }

    return response.data;
  };
}
