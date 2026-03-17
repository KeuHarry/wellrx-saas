import { getEcho } from '@/utils/echo';
import { ref, onMounted, onUnmounted } from 'vue';

/** Payload de l’événement broadcast NewNotificationEvent (Reverb). */
export interface NewNotificationPayload {
  title: string;
  message: string;
  type: string;
}

export type NotificationItem = NewNotificationPayload & { timestamp: Date };

/**
 * Souscrit au canal privé user.{userId} et écoute NewNotificationEvent.
 * Utilise une seule instance Echo (getEcho) pour une connexion WebSocket partagée.
 */
export function useNotifications(userId: number) {
  const notifications = ref<NotificationItem[]>([]);
  const echo = getEcho();

  onMounted(() => {
    echo
      .private(`user.${userId}`)
      .listen('.NewNotificationEvent', (data: NewNotificationPayload) => {
        notifications.value.unshift({
          ...data,
          timestamp: new Date(),
        });
      });
  });

  onUnmounted(() => {
    echo.leave(`user.${userId}`);
  });

  return { notifications };
}
