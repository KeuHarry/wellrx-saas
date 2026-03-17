import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { CashRegisterRequest } from '@/composables/models/CashRegisterRequest';

const axiosTenant = useSetupAxios();

export default function useCloseCashRegister(): (
  id: number,
  payload: CashRegisterRequest,
) => Promise<ApiResponse<number>> {
  return async (id: number, payload: CashRegisterRequest) => {
    const response = await axiosTenant.patch<ApiResponse<number>>(`/cash-registers/${id}`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/cash-registers, can't update cash register`);
    }

    return response.data;
  };
}
