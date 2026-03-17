import { Fee } from '@/composables/models/Fee';
import { SearchParams } from '@/composables/models/SearchParams';
import { FEE_CODE } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import useGetFees from '@/composables/api/fees/useGetFees';
import { FeeRequest } from '@/composables/models/FeeRequest';
import useCreateFee from '@/composables/api/fees/useCreateFee';
import useUpdateFee from '@/composables/api/fees/useUpdateFee';
import useDeleteFee from '@/composables/api/fees/useDeleteFee';

type FeeState = {
  fees: Fee[];
  fee: Fee | null;
  feeTotal: number;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useFeeStore = defineStore('fee-store', {
  state: (): FeeState => ({
    fees: [],
    fee: null,
    feeTotal: 0,
    searchParams: {
      search: '',
      tag: null,
      sortBy: FEE_CODE,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getFees: (state) => state.fees,
    getFee: (state) => state.fee,
    getFeeTotal: (state) => state.feeTotal,
  },

  actions: {
    async getFeesApi(searchParams: SearchParams | null = null) {
      const getFees = useGetFees(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getFees();
        this.fees = response.data ?? [];
        this.feeTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createFeeApi(feeRequest: FeeRequest) {
      const createFee = useCreateFee();
      try {
        this.isPending = true;
        const response = await createFee(feeRequest);
        this.fee = response.data;
        toastStore.setMessage('Fee successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateFeeApi(id: number, feeRequest: FeeRequest) {
      const udpateFee = useUpdateFee();
      try {
        this.isPending = true;
        const response = await udpateFee(id, feeRequest);
        toastStore.setMessage('Fee successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteFeeApi(id: number) {
      const deleteFee = useDeleteFee();
      try {
        this.isPending = true;
        const response = await deleteFee(id);
        if (response.success) {
          this.fees = this.fees.filter((fee) => fee.id !== id);
        }
        toastStore.setMessage('Fee successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useFeeStore;
