import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { ProfitLossSummary } from '@/composables/models/ProfitLossSummary';

const axiosTenant = useSetupAxios();

export default function useGetProfitLossReport(
  searchParams: SearchParams,
): () => Promise<ApiResponse<ProfitLossSummary>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<ProfitLossSummary>>(
        `/profit-loss-report?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/profit-loss-report: Failed to fetch the profit and loss report.`,
      );
    }
  };
}
