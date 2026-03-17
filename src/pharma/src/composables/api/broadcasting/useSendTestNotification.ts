import { axiosTenant, useSetupAxios } from '@/composables/api/useAxiosTenant';

/**
 * Envoie une requête au backend pour diffuser une notification test via Reverb.
 * Utile pour vérifier que la connexion WebSocket et l'écoute des événements fonctionnent.
 */
export async function useSendTestNotification(): Promise<void> {
  const client = axiosTenant ?? useSetupAxios();
  await client.post('broadcasting/test');
}
