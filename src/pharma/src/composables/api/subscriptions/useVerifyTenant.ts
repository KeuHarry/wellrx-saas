import axios from 'axios'
import { CENTRAL_URL } from '../../../utils/apiUrl'
import { RegistrationResponse } from '@/composables/models/RegistrationResponse'

export default function useVerifyTenant(): (account: string) => Promise<RegistrationResponse> {
  return async (account: string) => {
    const response = await axios.get<RegistrationResponse>(`${CENTRAL_URL}/verify/${account}`)

    if (!response) {
      throw new Error(`${CENTRAL_URL}/verify, can't verify tenant`)
    }

    return response.data
  }
}
