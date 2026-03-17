import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { buildQueryParams } from '@/utils/helper';
import { SaleReportItem } from '@/composables/models/SaleReportItem';

const axiosTenant = useSetupAxios();

export default function useGetPaidSalesReport(
  searchParams: SearchParams,
): () => Promise<ApiResponse<SaleReportItem[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<SaleReportItem[]>>(
        `/sales-report/paid?${queryParams}`,
      );
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/sales-report/paid: Failed to fetch the sales report.`,
      );
    }
  };
}
