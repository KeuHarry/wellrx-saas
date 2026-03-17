import { defineStore } from 'pinia';

export const useLoadingStore = defineStore('loading-store', {
  state: () => ({
    isLoading: false,
  }),
  getters: {
    getLoading: (state) => state.isLoading,
  },
  actions: {
    startLoading() {
      this.isLoading = true;
    },
    stopLoading() {
      this.isLoading = false;
    },
  },
});
