import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { CashRegister } from '@/composables/models/CashRegister';

const axiosTenant = useSetupAxios();

export default function useGetCashRegisters(
  searchParams: SearchParams,
): () => Promise<ApiResponse<CashRegister[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<CashRegister[]>>(
        `/cash-registers?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/cash-registers: Failed to fetch the cash register list.`,
      );
    }
  };
}
