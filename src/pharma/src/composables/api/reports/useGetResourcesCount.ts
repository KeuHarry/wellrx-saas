import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { ResourcesCount } from '@/composables/models/ResourcesCount';

const axiosTenant = useSetupAxios();

export default function useGetResourcesCount(): () => Promise<ApiResponse<ResourcesCount>> {
  return async () => {
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<ResourcesCount>>(`/reports/resources-count`);
      return response.data;
    } catch (error) {
      throw new Error(
        `${tenantStore.getTenantUrl}/reports/resources-count: Failed to fetch theresources count report.`,
      );
    }
  };
}
