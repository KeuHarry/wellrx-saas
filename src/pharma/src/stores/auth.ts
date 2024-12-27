import useCsrfCookie from '@/composables/api/authentication/useCsrfCookie'
import useLogin from '@/composables/api/authentication/useLogin'
import { AuthUser } from '@/composables/models/AuthUser'
import { Credentials } from '@/types/credentials'
import { defineStore } from 'pinia'

type TenantState = {
  authUser: AuthUser | null
  isPending: boolean
}

export const useAuthStore = defineStore('auth-store', {
  state: (): TenantState => ({
    authUser: null,
    isPending: false,
  }),

  getters: {
    getAuthUser: (state) => state.authUser,
  },

  actions: {
    async loginApi(credentials: Credentials) {
      const login = useLogin()
      try {
        this.isPending = true
        const response = await login(credentials)
        this.authUser = response.user
        sessionStorage.setItem('user', JSON.stringify(this.authUser))
        sessionStorage.setItem('authToken', response.token)
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },

    async csrfCookieApi() {
      const csrfCookie = useCsrfCookie()
      try {
        this.isPending = true
        await csrfCookie()
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
  },
})

export default useAuthStore
