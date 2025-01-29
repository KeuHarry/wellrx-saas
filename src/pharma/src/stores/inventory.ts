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

type InventoryState = {
  inventoryList: Inventory[];
  inventoryTotal: number;
  inventory: Inventory | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

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
    isPending: false,
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
        this.isPending = true;
        const response = await getInventoryList();
        this.inventoryList = response.data ?? [];
        this.inventoryTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createInventoryApi(inRequest: InRequest) {
      const createInventory = useCreateInventory();
      try {
        this.isPending = true;
        const response = await createInventory(inRequest);
        this.inventory = response.data;
        if (this.inventory) {
          toastStore.setMessage('Inventory entry successfully added!');
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteInventoryApi(id: number) {
      const deleteInventory = useDeleteInventory();
      try {
        this.isPending = true;
        const response = await deleteInventory(id);
        if (response.success) {
          this.inventoryList = this.inventoryList.filter((inventory) => inventory.id !== id);
        }
        toastStore.setMessage('Inventory entry successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async inventoryOutApi(outRequest: InventoryOutRequest) {
      const inventoryOut = useInventoryOut();
      try {
        this.isPending = true;
        const response = await inventoryOut(outRequest);
        this.inventory = response.data;
        if (this.inventory) {
          toastStore.setMessage('Inventory batch reduced!');
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useInventoryStore;
