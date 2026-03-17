import NotificationType from '@/enums/notification';
import { defineStore } from 'pinia';

type ToastState = {
  message: {
    type: number;
    content: string;
  };
};

export const useToastStore = defineStore('toast-store', {
  state: (): ToastState => ({
    message: {
      type: NotificationType.SUCCESS,
      content: '',
    },
  }),

  getters: {
    getMessage: (state) => state.message,
  },

  actions: {
    setMessage(message: string, type: number = NotificationType.SUCCESS) {
      this.message.type = type;
      this.message.content = message;
    },
  },
});

export default useToastStore;
