import useRegisterTenant from '@/composables/api/subscriptions/useRegisterTenant'
import useVerifyTenant from '@/composables/api/subscriptions/useVerifyTenant'
import { Tenant } from '@/composables/models/Tenant'
import { API_URL } from '@/utils/apiUrl'
import { defineStore } from 'pinia'
import { useRouter } from 'vue-router'

type TenantState = {
  tenantIdentifier: string
  tenantUrl: string
  isPending: boolean
}

export const useTenantStore = defineStore('tenant-store', {
  state: (): TenantState => ({
    tenantIdentifier: '',
    tenantUrl: '',
    isPending: false,
  }),

  getters: {
    getTenantIdentifier: (state) => state.tenantIdentifier,
    getTenantUrl: (state) => state.tenantUrl,
  },

  actions: {
    async registerTenantApi(tenant: Tenant) {
      const registerTenant = useRegisterTenant()
      try {
        this.isPending = true
        const response = await registerTenant(tenant)
        this.tenantIdentifier = response.tenant.identifier
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },

    async accountVerificationApi(account: string) {
      const router = useRouter()
      const verifyTenant = useVerifyTenant()
      try {
        this.isPending = true
        const response = await verifyTenant(account)
        this.tenantIdentifier = response.tenant.identifier
        this.tenantUrl = `${API_URL}/${response.tenant.id}/v1`
        return response
      } catch (error) {
        this.isPending = false
        router.push({ path: '/verification' })
        throw error
      }
    },
  },
})

export default useTenantStore
