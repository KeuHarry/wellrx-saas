import { SearchParams } from '@/composables/models/SearchParams';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Customer } from '@/composables/models/Customer';
import useGetCustomers from '@/composables/api/customers/useGetCustomers';
import { CustomerRequest } from '@/composables/models/CustomerRequest';
import useCreateCustomer from '@/composables/api/customers/useCreateCustomer';
import useUpdateCustomer from '@/composables/api/customers/useUpdateCustomer';
import useDeleteCustomer from '@/composables/api/customers/useDeleteCustomer';

type CustomerState = {
  customers: Customer[];
  customerTotal: number;
  customer: Customer | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useCustomerStore = defineStore('customer-store', {
  state: (): CustomerState => ({
    customers: [],
    customerTotal: 0,
    customer: null,
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
    getCustomers: (state) => state.customers,
    getCustomer: (state) => state.customer,
    getCustomerTotal: (state) => state.customerTotal,
  },

  actions: {
    async getCustomersApi() {
      const getCustomers = useGetCustomers(this.searchParams);
      try {
        this.isPending = true;
        const response = await getCustomers();
        this.customers = response.data ?? [];
        this.customerTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createCustomerApi(customerRequest: CustomerRequest) {
      const createCustomer = useCreateCustomer();
      try {
        this.isPending = true;
        const response = await createCustomer(customerRequest);
        this.customer = response.data;
        toastStore.setMessage('Customer successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateCustomerApi(id: number, customerRequest: CustomerRequest) {
      const udpateCustomer = useUpdateCustomer();
      try {
        this.isPending = true;
        const response = await udpateCustomer(id, customerRequest);
        toastStore.setMessage('Customer successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteCustomerApi(id: number) {
      const deleteCustomer = useDeleteCustomer();
      try {
        this.isPending = true;
        const response = await deleteCustomer(id);
        if (response.success) {
          this.customers = this.customers.filter((customer) => customer.id !== id);
        }
        toastStore.setMessage('Customer successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useCustomerStore;
