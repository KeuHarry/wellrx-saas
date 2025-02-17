import { SearchParams } from '@/composables/models/SearchParams';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import useGetOrders from '@/composables/api/orders/useGetOrders';
import { SaleReportItem } from '@/composables/models/SaleReportItem';
import useGetPaidSalesReport from '@/composables/api/reports/useGetPaidSalesReport';
import { useLoadingStore } from './loading';
import useGetProfitLossReport from '@/composables/api/reports/useGetProfitLossReport';
import { ProfitLossSummary } from '@/composables/models/ProfitLossSummary';

type ReportState = {
  paidSalesReport: SaleReportItem[];
  profitLossReport: ProfitLossSummary | null;
  paidSalesReportTotal: number;
  searchParams: SearchParams;
};

const toastStore = useToastStore();
const loadingStore = useLoadingStore();

export const useReportStore = defineStore('report-store', {
  state: (): ReportState => ({
    paidSalesReport: [],
    profitLossReport: null,
    paidSalesReportTotal: 0,
    searchParams: {
      search: '',
      sortOrder: SortOrder.DESC,
      offset: 0,
      limit: 10,
    },
  }),

  getters: {
    getPaidSalesReport: (state) => state.paidSalesReport,
    getPaidSalesReportTotal: (state) => state.paidSalesReportTotal,
    getProfitLossReport: (state) => state.profitLossReport,
  },

  actions: {
    async getPaidSalesReportApi(searchParams: SearchParams | null = null) {
      const getPaidSalesReport = useGetPaidSalesReport(
        searchParams ? searchParams : this.searchParams,
      );
      try {
        loadingStore.startLoading();
        const response = await getPaidSalesReport();
        loadingStore.stopLoading();
        this.paidSalesReport = response.data ?? [];
        this.paidSalesReportTotal = response.total ?? 0;
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
    async getProfitLossReportApi(searchParams: SearchParams | null = null) {
      const getProfitLossReport = useGetProfitLossReport(
        searchParams ? searchParams : this.searchParams,
      );
      try {
        loadingStore.startLoading();
        const response = await getProfitLossReport();
        loadingStore.stopLoading();
        this.profitLossReport = response.data;
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
  },
});

export default useReportStore;
