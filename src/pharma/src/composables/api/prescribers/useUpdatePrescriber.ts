import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';

const axiosTenant = useSetupAxios();

export default function useUpdatePrescriber(): (
  id: number,
  payload: PrescriberRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: PrescriberRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/prescribers/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/prescribers, can't update prescriber`);
    }

    return response.data;
  };
}
