import { SearchParams } from '@/composables/models/SearchParams';
import { CREATED_AT } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { CashRegister } from '@/composables/models/CashRegister';
import { CashRegisterRequest } from '@/composables/models/CashRegisterRequest';
import useGetCashRegisters from '@/composables/api/cashRegisters/useGetCashRegisters';
import useOpenCashRegister from '@/composables/api/cashRegisters/useOpenCashRegister';
import useCloseCashRegister from '@/composables/api/cashRegisters/useCloseCashRegister';

type CashRegisterState = {
  cashRegisters: CashRegister[];
  cashRegisterTotal: number;
  cashRegister: CashRegister | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useCashRegisterStore = defineStore('cash-register-store', {
  state: (): CashRegisterState => ({
    cashRegisters: [],
    cashRegisterTotal: 0,
    cashRegister: null,
    searchParams: {
      search: '',
      sortBy: CREATED_AT,
      sortOrder: SortOrder.DESC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getCashRegisters: (state) => state.cashRegisters,
    getCashRegister: (state) => state.cashRegister,
    getCashRegisterTotal: (state) => state.cashRegisterTotal,
  },

  actions: {
    async getCashRegistersApi(searchParams: SearchParams | null = null) {
      const getCashRegisters = useGetCashRegisters(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getCashRegisters();
        this.cashRegisters = response.data ?? [];
        this.cashRegisterTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async openCashRegisterApi(cashRegisterRequest: CashRegisterRequest) {
      const openCashRegister = useOpenCashRegister();
      try {
        this.isPending = true;
        const response = await openCashRegister(cashRegisterRequest);
        this.cashRegister = response.data;
        localStorage.setItem(
          'cashRegister',
          JSON.stringify({ id: this.cashRegister?.id, isOpen: this.cashRegister?.status }),
        );
        toastStore.setMessage('cash register opened!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async closeCashRegisterApi(id: number, cashRegisterRequest: CashRegisterRequest) {
      const closeCashRegister = useCloseCashRegister();
      try {
        this.isPending = true;
        const response = await closeCashRegister(id, cashRegisterRequest);
        localStorage.removeItem('cashRegister');
        toastStore.setMessage('cash register closed!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useCashRegisterStore;
