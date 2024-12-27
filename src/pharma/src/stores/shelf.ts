import useCreateShelf from '@/composables/api/shelves/useCreateShelf'
import useGetShelves from '@/composables/api/shelves/useGetShelves'
import { SearchParams } from '@/composables/models/SearchParams'
import { Shelf } from '@/composables/models/Shelf'
import { ShelfRequest } from '@/composables/models/ShelfRequest'
import { CODE } from '@/constants/properties'
import SortOrder from '@/enums/sortOrder'
import { defineStore } from 'pinia'

type ShelfState = {
  shelves: Shelf[]
  shelf: Shelf | null
  searchParams: SearchParams
  isPending: boolean
}

export const useShelfStore = defineStore('shelf-store', {
  state: (): ShelfState => ({
    shelves: [],
    shelf: null,
    searchParams: {
      search: '',
      sortBy: CODE,
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
      const getShelves = useGetShelves(this.searchParams)
      try {
        this.isPending = true
        const response = await getShelves()
        this.shelves = response.data ?? []
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
    async createShelfApi(shelfRequest: ShelfRequest) {
      const createShelf = useCreateShelf()
      try {
        this.isPending = true
        const response = await createShelf(shelfRequest)
        this.shelf = response.data
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
  },
})

export default useShelfStore
