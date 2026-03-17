import useTenantStore from '@/stores/tenant';
import { getEnv } from '@/utils/getEnv';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/** Exposition de Pusher sur window pour que Laravel Echo l'utilise. */
(window as unknown as Window & { Pusher: typeof Pusher }).Pusher = Pusher;

let echoInstance: Echo<'reverb'> | null = null;

/**
 * Retourne l'URL de base Reverb (host) pour la connexion WebSocket.
 * Utilise VITE_REVERB_HOST ou, en dev, le hostname actuel.
 */
function getReverbHost(): string {
  try {
    return getEnv('REVERB_HOST') || window.location.hostname;
  } catch {
    return window.location.hostname;
  }
}

/**
 * Retourne le port Reverb (ex: 6001).
 */
function getReverbPort(): number {
  try {
    const port = getEnv('REVERB_PORT');
    return port ? parseInt(port, 10) : 6001;
  } catch {
    return 6001;
  }
}

/**
 * Retourne la clé Reverb (obligatoire pour Pusher).
 * @throws Error si VITE_REVERB_APP_KEY est absent ou vide dans .env
 */
function getReverbAppKey(): string {
  const key = import.meta.env.VITE_REVERB_APP_KEY as string | undefined;
  if (!key || String(key).trim() === '') {
    throw new Error(
      'VITE_REVERB_APP_KEY est manquant. Ajoutez-le dans le .env du frontend pharma (ex: VITE_REVERB_APP_KEY=xxx, même valeur que REVERB_APP_KEY du backend).'
    );
  }
  return key.trim();
}

/**
 * Crée et retourne une instance Laravel Echo configurée pour Reverb.
 * À appeler une fois le tenant connu (utilisateur connecté).
 * Utilisée en interne par getEcho().
 */
function createEcho(): Echo<'reverb'> {
  const tenantStore = useTenantStore();
  const tenantUrl = tenantStore.getTenantUrl?.trim() ?? '';
  if (!tenantUrl) {
    throw new Error(
      'Echo: tenantUrl vide. Reconnectez-vous ou ouvrez la page de login du tenant pour initialiser l’URL (tenant store persisté en sessionStorage).'
    );
  }
  const scheme = getEnv('REVERB_SCHEME') || 'http';
  const key = getReverbAppKey();

  return new Echo({
    broadcaster: 'reverb',
    key,
    wsHost: getReverbHost(),
    wsPort: getReverbPort(),
    wssPort: scheme === 'https' ? 443 : 6001,
    forceTLS: scheme === 'https',
    disableStats: true,
    authEndpoint: `${tenantUrl}/broadcasting/auth`,
    enabledTransports: ['ws', 'wss'],
    auth: {
      headers: {
        Authorization: `Bearer ${sessionStorage.getItem('authToken') ?? ''}`,
      },
    },
  });
}

/**
 * Retourne l'instance Echo singleton (une seule connexion WebSocket).
 * Crée l'instance au premier appel si le tenant est disponible.
 */
export function getEcho(): Echo<'reverb'> {
  if (!echoInstance) {
    echoInstance = createEcho();
  }
  return echoInstance;
}

/**
 * Réinitialise l'instance Echo (ex: après déconnexion).
 * À appeler quand le tenant ou le token change.
 */
/**
 * Réinitialise l'instance Echo (ex: après déconnexion).
 * À appeler quand le tenant ou le token change.
 */
export function resetEcho(): void {
  echoInstance = null;
}

export default createEcho;
