/// <reference types="vite/client" />

declare global {
  interface Window {
    Pusher: typeof import('pusher-js').default;
  }
}

interface ImportMetaEnv {
  readonly VITE_APP_API_URL: string;
  readonly VITE_REVERB_APP_KEY: string;
  readonly VITE_REVERB_HOST: string;
  readonly VITE_REVERB_PORT: string;
  readonly VITE_REVERB_SCHEME: string;
}
