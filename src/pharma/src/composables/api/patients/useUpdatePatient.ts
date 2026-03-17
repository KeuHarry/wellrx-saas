import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PatientRequest } from '@/composables/models/PatientRequest';

const axiosTenant = useSetupAxios();

export default function useUpdatePatient(): (
  id: number,
  payload: PatientRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: PatientRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/patients/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/patients, can't update patient`);
    }

    return response.data;
  };
}
