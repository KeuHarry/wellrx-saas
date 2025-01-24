import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { InsurerRequest } from '@/composables/models/InsurerRequest';

const axiosTenant = useSetupAxios();

export default function useUpdateInsurer(): (
  id: number,
  payload: InsurerRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: InsurerRequest) => {
    const response = await axiosTenant.put<ApiResponse<number>>(`/insurers/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/insurers, can't update insurer`);
    }

    return response.data;
  };
}
