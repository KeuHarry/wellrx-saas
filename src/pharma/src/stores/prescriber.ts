import { SearchParams } from '@/composables/models/SearchParams';
import { FIRSTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Prescriber } from '@/composables/models/Prescriber';
import useGetPrescribers from '@/composables/api/prescribers/useGetPrescribers';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';
import useCreatePrescriber from '@/composables/api/prescribers/useCreatePrescriber';
import useUpdatePrescriber from '@/composables/api/prescribers/useUpdatePrescriber';
import useDeletePrescriber from '@/composables/api/prescribers/useDeletePrescriber';

type PrescriberState = {
  prescribers: Prescriber[];
  prescriberTotal: number;
  prescriber: Prescriber | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const usePrescriberStore = defineStore('prescriber-store', {
  state: (): PrescriberState => ({
    prescribers: [],
    prescriberTotal: 0,
    prescriber: null,
    searchParams: {
      search: '',
      sortBy: FIRSTNAME,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getPrescribers: (state) => state.prescribers,
    getPrescriber: (state) => state.prescriber,
    getPrescriberTotal: (state) => state.prescriberTotal,
  },

  actions: {
    async getPrescribersApi(searchParams: SearchParams | null = null) {
      const getPrescribers = useGetPrescribers(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getPrescribers();
        this.prescribers = response.data ?? [];
        this.prescriberTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createPrescriberApi(prescriberRequest: PrescriberRequest) {
      const createPrescriber = useCreatePrescriber();
      try {
        this.isPending = true;
        const response = await createPrescriber(prescriberRequest);
        this.prescriber = response.data;
        toastStore.setMessage('Prescriber successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updatePrescriberApi(id: number, prescriberRequest: PrescriberRequest) {
      const udpatePrescriber = useUpdatePrescriber();
      try {
        this.isPending = true;
        const response = await udpatePrescriber(id, prescriberRequest);
        toastStore.setMessage('Prescriber successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deletePrescriberApi(id: number) {
      const deletePrescriber = useDeletePrescriber();
      try {
        this.isPending = true;
        const response = await deletePrescriber(id);
        if (response.success) {
          this.prescribers = this.prescribers.filter((prescriber) => prescriber.id !== id);
        }
        toastStore.setMessage('Prescriber successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default usePrescriberStore;
