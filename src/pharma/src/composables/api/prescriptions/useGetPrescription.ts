import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Prescription } from '@/composables/models/Prescription';

const axiosTenant = useSetupAxios();

export default function useGetPrescription(): (id: number) => Promise<ApiResponse<Prescription>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Prescription>>(`/prescriptions/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/prescriptions, can't get prescription`);
    }

    return response.data;
  };
}
