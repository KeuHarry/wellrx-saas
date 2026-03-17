import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Setting } from '@/composables/models/Setting';
import useGetSettings from '@/composables/api/settings/useGetSettings';
import { SettingRequest } from '@/composables/models/SettingRequest';
import useUpdateSetting from '@/composables/api/settings/useUpdateSetting';

type SettingState = {
  settings: Setting[];
  isPending: boolean;
};

export const useSettingStore = defineStore('setting-store', {
  state: (): SettingState => ({
    settings: [],
    isPending: false,
  }),

  getters: {
    getSettings: (state) => state.settings,
  },

  actions: {
    async getSettingsApi() {
      const getSettings = useGetSettings();
      try {
        this.isPending = true;
        const response = await getSettings();
        this.settings = response.data ?? [];
        localStorage.setItem('settings', JSON.stringify(this.settings));
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateSettingApi(settingRequest: SettingRequest) {
      const udpateSetting = useUpdateSetting();
      try {
        this.isPending = true;
        const response = await udpateSetting(settingRequest);
        this.settings = response.data ?? [];
        localStorage.setItem('settings', JSON.stringify(this.settings));
        const toastStore = useToastStore();
        toastStore.setMessage('Settings successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useSettingStore;
