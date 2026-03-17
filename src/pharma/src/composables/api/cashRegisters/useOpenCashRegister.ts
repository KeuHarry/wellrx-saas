import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CashRegister } from '@/composables/models/CashRegister';
import { CashRegisterRequest } from '@/composables/models/CashRegisterRequest';

const axiosTenant = useSetupAxios();

export default function useOpenCashRegister(): (
  payload: CashRegisterRequest,
) => Promise<ApiResponse<CashRegister>> {
  return async (payload: CashRegisterRequest) => {
    const response = await axiosTenant.post<ApiResponse<CashRegister>>(`/cash-registers`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/cash-registers, can't create cash register`);
    }

    return response.data;
  };
}
