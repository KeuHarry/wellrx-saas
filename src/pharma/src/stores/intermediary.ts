import { SearchParams } from '@/composables/models/SearchParams';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Intermediary } from '@/composables/models/Intermediary';
import useGetIntermediaries from '@/composables/api/intermediaries/useGetIntermediaries';
import { IntermediaryRequest } from '@/composables/models/IntermediaryRequest';
import useCreateIntermediary from '@/composables/api/intermediaries/useCreateIntermediary';
import useUpdateIntermediary from '@/composables/api/intermediaries/useUpdateIntermediary';
import useDeleteIntermediary from '@/composables/api/intermediaries/useDeleteIntermediary';

type IntermediaryState = {
  intermediaries: Intermediary[];
  intermediaryTotal: number;
  intermediary: Intermediary | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useIntermediaryStore = defineStore('intermediary-store', {
  state: (): IntermediaryState => ({
    intermediaries: [],
    intermediaryTotal: 0,
    intermediary: null,
    searchParams: {
      search: '',
      sortBy: NAME,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getIntermediaries: (state) => state.intermediaries,
    getIntermediary: (state) => state.intermediary,
    getIntermediaryTotal: (state) => state.intermediaryTotal,
  },

  actions: {
    async getIntermediariesApi() {
      const getIntermediaries = useGetIntermediaries(this.searchParams);
      try {
        this.isPending = true;
        const response = await getIntermediaries();
        this.intermediaries = response.data ?? [];
        this.intermediaryTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createIntermediaryApi(intermediaryRequest: IntermediaryRequest) {
      const createIntermediary = useCreateIntermediary();
      try {
        this.isPending = true;
        const response = await createIntermediary(intermediaryRequest);
        this.intermediary = response.data;
        toastStore.setMessage('Intermediary successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateIntermediaryApi(id: number, intermediaryRequest: IntermediaryRequest) {
      const udpateIntermediary = useUpdateIntermediary();
      try {
        this.isPending = true;
        const response = await udpateIntermediary(id, intermediaryRequest);
        toastStore.setMessage('Intermediary successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteIntermediaryApi(id: number) {
      const deleteIntermediary = useDeleteIntermediary();
      try {
        this.isPending = true;
        const response = await deleteIntermediary(id);
        if (response.success) {
          this.intermediaries = this.intermediaries.filter(
            (intermediary) => intermediary.id !== id,
          );
        }
        toastStore.setMessage('Intermediary successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useIntermediaryStore;
