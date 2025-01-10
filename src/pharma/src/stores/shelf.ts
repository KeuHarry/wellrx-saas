import useCreateShelf from '@/composables/api/shelves/useCreateShelf';
import useGetShelves from '@/composables/api/shelves/useGetShelves';
import useUpdateShelf from '@/composables/api/shelves/useUpdateShelf';
import { SearchParams } from '@/composables/models/SearchParams';
import { Shelf } from '@/composables/models/Shelf';
import { ShelfRequest } from '@/composables/models/ShelfRequest';
import { SHELF_CODE } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import useDeleteShelf from '@/composables/api/shelves/useDeleteShelf';

type ShelfState = {
  shelves: Shelf[];
  shelf: Shelf | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useShelfStore = defineStore('shelf-store', {
  state: (): ShelfState => ({
    shelves: [],
    shelf: null,
    searchParams: {
      search: '',
      sortBy: SHELF_CODE,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getShelves: (state) => state.shelves,
    getShelf: (state) => state.shelf,
  },

  actions: {
    async getShelvesApi() {
      const getShelves = useGetShelves(this.searchParams);
      try {
        this.isPending = true;
        const response = await getShelves();
        this.shelves = response.data ?? [];
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createShelfApi(shelfRequest: ShelfRequest) {
      const createShelf = useCreateShelf();
      try {
        this.isPending = true;
        const response = await createShelf(shelfRequest);
        this.shelf = response.data;
        toastStore.setMessage('Shelf successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateShelfApi(id: number, shelfRequest: ShelfRequest) {
      const udpateShelf = useUpdateShelf();
      try {
        this.isPending = true;
        const response = await udpateShelf(id, shelfRequest);
        toastStore.setMessage('Shelf successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteShelfApi(id: number) {
      const deleteShelf = useDeleteShelf();
      try {
        this.isPending = true;
        const response = await deleteShelf(id);
        if (response.success) {
          this.shelves = this.shelves.filter((shelf) => shelf.id !== id);
        }
        toastStore.setMessage('Shelf successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useShelfStore;
