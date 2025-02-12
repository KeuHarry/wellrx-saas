import { SearchParams } from '@/composables/models/SearchParams';
import { CREATED_AT } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Sale } from '@/composables/models/Sale';
import { SaleFragment } from '@/composables/models/SaleFragment';
import useGetSales from '@/composables/api/sales/useGetSales';
import useCreateSale from '@/composables/api/sales/useCreateSale';
import useUpdateSale from '@/composables/api/sales/useUpdateSale';
import useDeleteSale from '@/composables/api/sales/useDeleteSale';
import useGetSale from '@/composables/api/sales/useGetSale';
import { SaleRequest } from '@/composables/models/SaleRequest';
import SaleStatus from '@/enums/saleStatus';
import { usePrintSaleInvoice } from '@/composables/api/sales/usePrintSaleInvoice';
import { usePrintSaleReceipt } from '@/composables/api/sales/usePrintSaleReceipt';
import usePaySale from '@/composables/api/sales/usePaySale';
import { CashierOneTimePaymentRequest } from '@/composables/models/CashierRequest';
import useGetUnPaidSales from '@/composables/api/sales/useGetUnPaidSales';
import { usePrintSaleProforma } from '@/composables/api/sales/usePrintSaleProforma';

type SaleState = {
  sales: SaleFragment[];
  saleTotal: number;
  sale: Sale | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useSaleStore = defineStore('sale-store', {
  state: (): SaleState => ({
    sales: [],
    saleTotal: 0,
    sale: null,
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
    getSales: (state) => state.sales,
    getSale: (state) => state.sale,
    getSaleTotal: (state) => state.saleTotal,
  },

  actions: {
    async getSalesApi(searchParams: SearchParams | null = null) {
      const getSales = useGetSales(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getSales();
        this.sales = response.data ?? [];
        this.saleTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getUnPaidSalesApi(searchParams: SearchParams | null = null) {
      const getSales = useGetUnPaidSales(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getSales();
        this.sales = response.data ?? [];
        this.saleTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createSaleApi(saleRequest: SaleRequest) {
      const createSale = useCreateSale();
      try {
        this.isPending = true;
        const response = await createSale(saleRequest);
        this.sale = response.data;
        if (saleRequest.status === SaleStatus.COMPLETED) {
          toastStore.setMessage('Sale successfully completed!');
        } else {
          toastStore.setMessage('Sale successfully saved!');
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateSaleApi(id: number, saleRequest: SaleRequest) {
      const udpateSale = useUpdateSale();
      try {
        this.isPending = true;
        const response = await udpateSale(id, saleRequest);
        toastStore.setMessage('Sale successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteSaleApi(id: number) {
      const deleteSale = useDeleteSale();
      try {
        this.isPending = true;
        const response = await deleteSale(id);
        if (response.success) {
          this.sales = this.sales.filter((sale) => sale.id !== id);
        }
        toastStore.setMessage('Sale successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getSaleApi(id: number) {
      const getSale = useGetSale();
      try {
        this.isPending = true;
        const response = await getSale(id);
        if (response.success) {
          this.sale = response.data;
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    printSaleInvoiceApi(id: number) {
      const { printPreview } = usePrintSaleInvoice();

      try {
        this.isPending = true;
        printPreview(id);
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async printSaleReceiptApi(id: number) {
      const { printPreview } = usePrintSaleReceipt();

      try {
        this.isPending = true;
        printPreview(id);
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async printSaleProformaApi(id: number) {
      const { printPreview } = usePrintSaleProforma();

      try {
        this.isPending = true;
        printPreview(id);
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async paySaleApi(payOneTimeRequest: CashierOneTimePaymentRequest) {
      const paySale = usePaySale();
      try {
        this.isPending = true;
        const response = await paySale(payOneTimeRequest);
        this.sale = response.data;
        if (this.sale?.id) {
          toastStore.setMessage('Payment done!');
          await this.printSaleReceiptApi(this.sale.id);
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useSaleStore;
