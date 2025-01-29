import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { InRequest } from '@/composables/models/InRequest';
import { Inventory } from '@/composables/models/Inventory';

const axiosTenant = useSetupAxios();

export default function useCreateInventory(): (
  payload: InRequest,
) => Promise<ApiResponse<Inventory>> {
  return async (payload: InRequest) => {
    const response = await axiosTenant.post<ApiResponse<Inventory>>(`/inventories`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/inventories, can't create inventory entry`);
    }

    return response.data;
  };
}
