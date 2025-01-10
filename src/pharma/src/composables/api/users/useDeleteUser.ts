import axiosTenant from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';
import { ApiResponse } from '@/composables/models/ApiResponse';

const tenantStore = useTenantStore();

export default function useDeleteUser(): (id: number) => Promise<ApiResponse<number>> {
  return async (id: number) => {
    const response = await axiosTenant.delete<ApiResponse<number>>(`/users/${id}`);

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/users, can't delete user`);
    }

    return response.data;
  };
}
