import useTenantStore from '@/stores/tenant';
import Echo from 'laravel-echo';
import Pusher from "pusher-js";

(window as any).Pusher = Pusher;

const createEcho = () => {
  const tenantStore = useTenantStore();
  console.log(tenantStore.getTenantUrl) 

  return new Echo({
    broadcaster: 'reverb',
    key: "ooirceotj0colbfguzpj",
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    authEndpoint: `${tenantStore.getTenantUrl}/broadcasting/auth`,
    enabledTransports: ['ws', 'wss'],
    auth: {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    },
  });
}


export default createEcho;
