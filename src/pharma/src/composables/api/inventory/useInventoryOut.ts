import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { InventoryOutRequest } from '@/composables/models/InventoryOutRequest';
import { Inventory } from '@/composables/models/Inventory';

const axiosTenant = useSetupAxios();

export default function useInventoryOut(): (
  payload: InventoryOutRequest,
) => Promise<ApiResponse<Inventory>> {
  return async (payload: InventoryOutRequest) => {
    const response = await axiosTenant.post<ApiResponse<Inventory>>(`/inventories/out`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/inventories/out, can't perfom inventory out`);
    }

    return response.data;
  };
}
