import createEcho from '@/utils/echo';
import { ref, onMounted } from 'vue';

export function useNotifications(userId: number) {
  const notifications = ref<{ title: string; message: string; type: string; timestamp: Date }[]>([]);
  const echo = createEcho();

  onMounted(() => {
    echo.private(`user.${userId}`).listen('NewNotificationEvent', (data: any) => {
      notifications.value.unshift({
        title: data.title,
        message: data.message,
        type: data.type,
        timestamp: new Date(),
      });
    });
  });

  return { notifications };
}
