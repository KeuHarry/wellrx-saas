import useCreateCategory from '@/composables/api/categories/useCreateCategory'
import useGetCategories from '@/composables/api/categories/useGetCategories'
import { Category } from '@/composables/models/Category'
import { CategoryRequest } from '@/composables/models/CategoryRequest'
import { SearchParams } from '@/composables/models/SearchParams'
import { CODE } from '@/constants/properties'
import SortOrder from '@/enums/sortOrder'
import { defineStore } from 'pinia'

type CategoryState = {
  categories: Category[]
  category: Category | null
  searchParams: SearchParams
  isPending: boolean
}

export const useCategoryStore = defineStore('category-store', {
  state: (): CategoryState => ({
    categories: [],
    category: null,
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
    getCategories: (state) => state.categories,
    getCategory: (state) => state.category,
  },

  actions: {
    async getCategoriesApi() {
      const getCategories = useGetCategories(this.searchParams)
      try {
        this.isPending = true
        const response = await getCategories()
        this.categories = response.data ?? []
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
    async createCategoryApi(categoryRequest: CategoryRequest) {
      const createCategory = useCreateCategory()
      try {
        this.isPending = true
        const response = await createCategory(categoryRequest)
        this.category = response.data
        return response
      } catch (error) {
        this.isPending = false
        throw error
      }
    },
  },
})

export default useCategoryStore
