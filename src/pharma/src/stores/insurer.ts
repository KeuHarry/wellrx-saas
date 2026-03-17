import { SearchParams } from '@/composables/models/SearchParams';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Insurer } from '@/composables/models/Insurer';
import useGetInsurers from '@/composables/api/insurers/useGetInsurers';
import useCreateInsurer from '@/composables/api/insurers/useCreateInsurer';
import { InsurerRequest } from '@/composables/models/InsurerRequest';
import useUpdateInsurer from '@/composables/api/insurers/useUpdateInsurer';
import useDeleteInsurer from '@/composables/api/insurers/useDeleteInsurer';

type InsurerState = {
  insurers: Insurer[];
  insurerTotal: number;
  insurer: Insurer | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useInsurerStore = defineStore('insurer-store', {
  state: (): InsurerState => ({
    insurers: [],
    insurerTotal: 0,
    insurer: null,
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
    getInsurers: (state) => state.insurers,
    getInsurer: (state) => state.insurer,
    getInsurerTotal: (state) => state.insurerTotal,
  },

  actions: {
    async getInsurersApi(searchParams: SearchParams | null = null) {
      const getInsurers = useGetInsurers(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getInsurers();
        this.insurers = response.data ?? [];
        this.insurerTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createInsurerApi(insurerRequest: InsurerRequest) {
      const createInsurer = useCreateInsurer();
      try {
        this.isPending = true;
        const response = await createInsurer(insurerRequest);
        this.insurer = response.data;
        toastStore.setMessage('Insurer successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateInsurerApi(id: number, insurerRequest: InsurerRequest) {
      const udpateInsurer = useUpdateInsurer();
      try {
        this.isPending = true;
        const response = await udpateInsurer(id, insurerRequest);
        toastStore.setMessage('Insurer successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteInsurerApi(id: number) {
      const deleteInsurer = useDeleteInsurer();
      try {
        this.isPending = true;
        const response = await deleteInsurer(id);
        if (response.success) {
          this.insurers = this.insurers.filter((insurer) => insurer.id !== id);
        }
        toastStore.setMessage('Insurer successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useInsurerStore;
