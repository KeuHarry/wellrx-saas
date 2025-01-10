import { SearchParams } from '@/composables/models/SearchParams';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Supplier } from '@/composables/models/Supplier';
import { SupplierRequest } from '@/composables/models/SupplierRequest';
import useCreateSupplier from '@/composables/api/suppliers/useCreateSupplier';
import useGetSuppliers from '@/composables/api/suppliers/useGetSuppliers';
import useUpdateSupplier from '@/composables/api/suppliers/useUpdateSupplier';
import useDeleteSupplier from '@/composables/api/suppliers/useDeleteSupplier';

type SupplierState = {
  suppliers: Supplier[];
  supplierTotal: number;
  supplier: Supplier | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useSupplierStore = defineStore('supplier-store', {
  state: (): SupplierState => ({
    suppliers: [],
    supplierTotal: 0,
    supplier: null,
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
    getSuppliers: (state) => state.suppliers,
    getSupplier: (state) => state.supplier,
    getSupplierTotal: (state) => state.supplierTotal,
  },

  actions: {
    async getSuppliersApi() {
      const getSuppliers = useGetSuppliers(this.searchParams);
      try {
        this.isPending = true;
        const response = await getSuppliers();
        this.suppliers = response.data ?? [];
        this.supplierTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createSupplierApi(supplierRequest: SupplierRequest) {
      const createSupplier = useCreateSupplier();
      try {
        this.isPending = true;
        const response = await createSupplier(supplierRequest);
        this.supplier = response.data;
        toastStore.setMessage('Supplier successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateSupplierApi(id: number, supplierRequest: SupplierRequest) {
      const udpateSupplier = useUpdateSupplier();
      try {
        this.isPending = true;
        const response = await udpateSupplier(id, supplierRequest);
        toastStore.setMessage('Supplier successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteSupplierApi(id: number) {
      const deleteSupplier = useDeleteSupplier();
      try {
        this.isPending = true;
        const response = await deleteSupplier(id);
        if (response.success) {
          this.suppliers = this.suppliers.filter((supplier) => supplier.id !== id);
        }
        toastStore.setMessage('Supplier successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useSupplierStore;
