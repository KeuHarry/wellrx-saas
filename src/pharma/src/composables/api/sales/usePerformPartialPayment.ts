import { useSetupAxios } from '../useAxiosTenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import useTenantStore from '@/stores/tenant';
import { Sale } from '@/composables/models/Sale';
import { PartialPaymentRequest } from '@/composables/models/PartialPaymentRequest';

const axiosTenant = useSetupAxios();

export default function usePerformPartialPayment(): (
  payload: PartialPaymentRequest,
) => Promise<ApiResponse<Sale>> {
  return async (payload: PartialPaymentRequest) => {
    const response = await axiosTenant.post<ApiResponse<Sale>>(`/sales/partial-payment`, payload);

    if (!response) {
      const tenantStore = useTenantStore();
      throw new Error(`${tenantStore.getTenantUrl}/sales/partial-payment, can't make a payment`);
    }

    return response.data;
  };
}
