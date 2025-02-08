import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CashRegister } from '@/composables/models/CashRegister';

const axiosTenant = useSetupAxios();

export default function useGetCurrentCashRegister(): () => Promise<ApiResponse<CashRegister>> {
  return async () => {
    const response = await axiosTenant.get<ApiResponse<CashRegister>>(`/current-cash-register`);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/current-cash-register, can't get cash register`);
    }

    return response.data;
  };
}
