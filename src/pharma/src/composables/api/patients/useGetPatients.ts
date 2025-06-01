import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';
import { SearchParams } from '@/composables/models/SearchParams';
import { Patient } from '@/composables/models/Patient';
import { buildQueryParams } from '@/utils/helper';

const axiosTenant = useSetupAxios();

export default function useGetPatients(
  searchParams: SearchParams,
): () => Promise<ApiResponse<Patient[]>> {
  return async () => {
    const queryParams = buildQueryParams(searchParams);
    const tenantStore = useTenantStore();

    try {
      const response = await axiosTenant.get<ApiResponse<Patient[]>>(`/patients?${queryParams}`);
      return response.data;
    } catch (error) {
      throw new Error(`${tenantStore.getTenantUrl}/patients, can't get patients list`);
    }
  };
}
