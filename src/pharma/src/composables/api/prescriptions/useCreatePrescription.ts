import { Prescription } from '@/composables/models/Prescription';
import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { PrescriptionRequest } from '@/composables/models/PrescriptionRequest';

const axiosTenant = useSetupAxios();

export default function useCreatePrescription(): (
  payload: PrescriptionRequest,
) => Promise<ApiResponse<Prescription>> {
  return async (payload: PrescriptionRequest) => {
    const response = await axiosTenant.post<ApiResponse<Prescription>>(`/prescriptions`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/prescriptions, can't create prescription`);
    }

    return response.data;
  };
}
