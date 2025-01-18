import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const axiosTenant = useSetupAxios();

export default function useDeleteFee(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/fees/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/fees, can't delete fee`);
    }

    return response.data;
  };
}
