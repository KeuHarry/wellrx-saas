import axiosTenant from '../useAxiosTenant'
import { CategoryRequest } from '@/composables/models/CategoryRequest'
import { Category } from '@/composables/models/Category'
import useTenantStore from '@/stores/tenant'
import { ApiResponse } from '@/composables/models/ApiResponse'

const tenantStore = useTenantStore()

export default function useCreateCategory(): (
  payload: CategoryRequest,
) => Promise<ApiResponse<Category>> {
  return async (payload: CategoryRequest) => {
    const response = await axiosTenant.post<ApiResponse<Category>>(`/categories`, payload)

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/categories, can't create category`)
    }

    return response.data
  }
}
