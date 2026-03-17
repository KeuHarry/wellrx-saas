import { SearchParams } from '@/composables/models/SearchParams';
import { CREATED_AT } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Inventory } from '@/composables/models/Inventory';
import useGetInventoryList from '@/composables/api/inventory/useGetInventoryList';
import useCreateInventory from '@/composables/api/inventory/useCreateInventory';
import useDeleteInventory from '@/composables/api/inventory/useDeleteInventory';
import { InRequest } from '@/composables/models/InRequest';
import useInventoryOut from '@/composables/api/inventory/useInventoryOut';
import { InventoryOutRequest } from '@/composables/models/InventoryOutRequest';
import { useLoadingStore } from './loading';

type InventoryState = {
  inventoryList: Inventory[];
  inventoryTotal: number;
  inventory: Inventory | null;
  searchParams: SearchParams;
};

const toastStore = useToastStore();
const loadingStore = useLoadingStore();

export const useInventoryStore = defineStore('inventory-store', {
  state: (): InventoryState => ({
    inventoryList: [],
    inventoryTotal: 0,
    inventory: null,
    searchParams: {
      search: '',
      sortBy: CREATED_AT,
      sortOrder: SortOrder.DESC,
      offset: 0,
      limit: 10,
      type: null,
    },
  }),

  getters: {
    getInventoryList: (state) => state.inventoryList,
    getInventory: (state) => state.inventory,
    getInventoryTotal: (state) => state.inventoryTotal,
  },

  actions: {
    async getInventoryListApi(searchParams: SearchParams | null = null) {
      const getInventoryList = useGetInventoryList(searchParams ? searchParams : this.searchParams);
      try {
        loadingStore.startLoading();
        const response = await getInventoryList();
        loadingStore.stopLoading();
        this.inventoryList = response.data ?? [];
        this.inventoryTotal = response.total ?? 0;
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
    async createInventoryApi(inRequest: InRequest) {
      const createInventory = useCreateInventory();
      try {
        loadingStore.startLoading();
        const response = await createInventory(inRequest);
        this.inventory = response.data;
        if (this.inventory) {
          loadingStore.stopLoading();
          toastStore.setMessage('Inventory entry successfully added!');
        }
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
    async deleteInventoryApi(id: number) {
      const deleteInventory = useDeleteInventory();
      try {
        loadingStore.startLoading();
        const response = await deleteInventory(id);
        if (response.success) {
          loadingStore.stopLoading();
          this.inventoryList = this.inventoryList.filter((inventory) => inventory.id !== id);
        }
        toastStore.setMessage('Inventory entry successfully deleted!');
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
    async inventoryOutApi(outRequest: InventoryOutRequest) {
      const inventoryOut = useInventoryOut();
      try {
        loadingStore.startLoading();
        const response = await inventoryOut(outRequest);
        this.inventory = response.data;
        if (this.inventory) {
          loadingStore.stopLoading();
          toastStore.setMessage('Inventory batch reduced!');
        }
        return response;
      } catch (error) {
        loadingStore.stopLoading();
        throw error;
      }
    },
  },
});

export default useInventoryStore;
