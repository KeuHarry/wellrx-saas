import axios from 'axios';
import { CENTRAL_URL } from '../../../utils/apiUrl';
import { RegistrationResponse } from '@/composables/models/RegistrationResponse';
import { Tenant } from '@/composables/models/Tenant';

export default function useRegisterTenant(): (payload: Tenant) => Promise<RegistrationResponse> {
  return async (payload: Tenant) => {
    const response = await axios.post<RegistrationResponse>(`${CENTRAL_URL}/register`, payload);

    if (!response) {
      throw new Error(`${CENTRAL_URL}/register, can't create tenant`);
    }

    return response.data;
  };
}
