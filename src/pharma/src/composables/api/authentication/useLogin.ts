import axios from 'axios'
import { Credentials } from '@/types/credentials'
import { AuthUser } from '@/composables/models/AuthUser'
import useTenantStore from '@/stores/tenant'
import axiosTenant from '../useAxiosTenant'
import { AuthResponse } from '@/composables/models/AuthResponse'

const tenantStore = useTenantStore()

export default function useLogin(): (payload: Credentials) => Promise<AuthResponse> {
  return async (payload: Credentials) => {
    const response = await axiosTenant.post<AuthResponse>('/login', payload)

    if (!response) {
      throw new Error(
        `${tenantStore.getTenantUrl}/login, can't login. Please verify your credentials`,
      )
    }

    return response.data
  }
}
