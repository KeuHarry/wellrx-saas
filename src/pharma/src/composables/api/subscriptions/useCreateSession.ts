import axios from 'axios';
import { CENTRAL_URL } from '../../../utils/apiUrl';
import { StripeSession } from '@/composables/models/StripeSession';

export default function useCreateSession(): (priceId: string) => Promise<StripeSession> {
  return async (priceId: string) => {
    const response = await axios.post<StripeSession>(`${CENTRAL_URL}/create-session`, {
      priceId: priceId,
    });

    if (!response) {
      throw new Error(`${CENTRAL_URL}/create-session, can't get session`);
    }

    return response.data;
  };
}
