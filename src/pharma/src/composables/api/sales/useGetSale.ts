import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { Sale } from '@/composables/models/Sale';

const axiosTenant = useSetupAxios();

export default function useGetSale(): (id: number) => Promise<ApiResponse<Sale>> {
  return async (id: number) => {
    const response = await axiosTenant.get<ApiResponse<Sale>>(`/sales/${id}`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales, can't get sale`);
    }

    return response.data;
  };
}
