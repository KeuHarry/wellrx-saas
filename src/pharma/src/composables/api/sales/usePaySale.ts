import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { Sale } from '@/composables/models/Sale';
import { CashierOneTimePaymentRequest } from '@/composables/models/CashierRequest';

const axiosTenant = useSetupAxios();

export default function usePaySale(): (
  payload: CashierOneTimePaymentRequest,
) => Promise<ApiResponse<Sale>> {
  return async (payload: CashierOneTimePaymentRequest) => {
    const response = await axiosTenant.post<ApiResponse<Sale>>(`/sales/pay`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales/pay, can't do the payment`);
    }

    return response.data;
  };
}
