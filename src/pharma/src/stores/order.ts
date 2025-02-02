import { SearchParams } from '@/composables/models/SearchParams';
import { INVOICE_DATE } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Order } from '@/composables/models/Order';
import useGetOrders from '@/composables/api/orders/useGetOrders';
import { OrderRequest } from '@/composables/models/OrderRequest';
import useCreateOrder from '@/composables/api/orders/useCreateOrder';
import useUpdateOrder from '@/composables/api/orders/useUpdateOrder';
import useDeleteOrder from '@/composables/api/orders/useDeleteOrder';
import useGetOrder from '@/composables/api/orders/useGetOrder';
import { CompleteOrderRequest } from '@/composables/models/CompleteOrderRequest';
import useCompleteOrder from '@/composables/api/orders/useCompleteOrder';

type OrderState = {
  orders: Order[];
  orderTotal: number;
  order: Order | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useOrderStore = defineStore('order-store', {
  state: (): OrderState => ({
    orders: [],
    orderTotal: 0,
    order: null,
    searchParams: {
      search: '',
      sortBy: INVOICE_DATE,
      sortOrder: SortOrder.DESC,
      offset: 0,
      limit: 10,
      shelfId: null,
    },
    isPending: false,
  }),

  getters: {
    getOrders: (state) => state.orders,
    getOrder: (state) => state.order,
    getOrderTotal: (state) => state.orderTotal,
  },

  actions: {
    async getOrdersApi(searchParams: SearchParams | null = null) {
      const getOrders = useGetOrders(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getOrders();
        this.orders = response.data ?? [];
        this.orderTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createOrderApi(orderRequest: OrderRequest) {
      const createOrder = useCreateOrder();
      try {
        this.isPending = true;
        const response = await createOrder(orderRequest);
        this.order = response.data;
        toastStore.setMessage('Order successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateOrderApi(id: number, orderRequest: OrderRequest) {
      const udpateOrder = useUpdateOrder();
      try {
        this.isPending = true;
        const response = await udpateOrder(id, orderRequest);
        toastStore.setMessage('Order successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteOrderApi(id: number) {
      const deleteOrder = useDeleteOrder();
      try {
        this.isPending = true;
        const response = await deleteOrder(id);
        if (response.success) {
          this.orders = this.orders.filter((order) => order.id !== id);
        }
        toastStore.setMessage('Order successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getOrderApi(id: number) {
      const getOrder = useGetOrder();
      try {
        this.isPending = true;
        const response = await getOrder(id);
        if (response.success) {
          this.order = response.data;
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async completeOrderApi(request: CompleteOrderRequest) {
      const completeOrder = useCompleteOrder();
      try {
        this.isPending = true;
        const response = await completeOrder(request);
        this.order = response.data;
        toastStore.setMessage('Order successfully completed!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useOrderStore;
