import useCreateSession from '@/composables/api/subscriptions/useCreateSession'
import { defineStore } from 'pinia'

type SubscriptionState = {
  sessionId: string
  isPending: boolean
}

export const useSubscriptionStore = defineStore('subscription-store', {
  state: (): SubscriptionState => ({
    sessionId: '',
    isPending: false,
  }),

  getters: {
    getStripeSessionId: (state) => state.sessionId,
  },

  actions: {
    async createSessionApi(priceId: string) {
      const createSession = useCreateSession()
      try {
        this.isPending = true
        const stripeSession = await createSession(priceId)
        this.sessionId = stripeSession.sessionId
        console.log(stripeSession)
        return stripeSession
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
  },
})

export default useSubscriptionStore
