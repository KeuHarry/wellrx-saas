import axiosTenant from '../useAxiosTenant'
import useTenantStore from '@/stores/tenant'
import { ApiResponse } from '@/composables/models/ApiResponse'
import { ShelfRequest } from '@/composables/models/ShelfRequest'
import { Shelf } from '@/composables/models/Shelf'

const tenantStore = useTenantStore()

export default function useCreateShelf(): (payload: ShelfRequest) => Promise<ApiResponse<Shelf>> {
  return async (payload: ShelfRequest) => {
    const response = await axiosTenant.post<ApiResponse<Shelf>>(`/shelves`, payload)

    if (!response) {
      throw new Error(`${tenantStore.getTenantUrl}/shelves, can't create shelf`)
    }

    return response.data
  }
}
