import useCreateCategory from '@/composables/api/categories/useCreateCategory';
import useGetCategories from '@/composables/api/categories/useGetCategories';
import { Category } from '@/composables/models/Category';
import { CategoryRequest } from '@/composables/models/CategoryRequest';
import { SearchParams } from '@/composables/models/SearchParams';
import { CATEGORY_CODE } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import useUpdateCategory from '@/composables/api/categories/useUpdateCategory';
import useDeleteCategory from '@/composables/api/categories/useDeleteCategory';

type CategoryState = {
  categories: Category[];
  category: Category | null;
  categoryTotal: number;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useCategoryStore = defineStore('category-store', {
  state: (): CategoryState => ({
    categories: [],
    category: null,
    categoryTotal: 0,
    searchParams: {
      search: '',
      sortBy: CATEGORY_CODE,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getCategories: (state) => state.categories,
    getCategory: (state) => state.category,
    getCategoryTotal: (state) => state.categoryTotal,
  },

  actions: {
    async getCategoriesApi(searchParams: SearchParams | null = null) {
      const getCategories = useGetCategories(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getCategories();
        this.categories = response.data ?? [];
        this.categoryTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createCategoryApi(categoryRequest: CategoryRequest) {
      const createCategory = useCreateCategory();
      try {
        this.isPending = true;
        const response = await createCategory(categoryRequest);
        this.category = response.data;
        toastStore.setMessage('Category successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateCategoryApi(id: number, categoryRequest: CategoryRequest) {
      const udpateCategory = useUpdateCategory();
      try {
        this.isPending = true;
        const response = await udpateCategory(id, categoryRequest);
        toastStore.setMessage('Category successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteCategoryApi(id: number) {
      const deleteCategory = useDeleteCategory();
      try {
        this.isPending = true;
        const response = await deleteCategory(id);
        if (response.success) {
          this.categories = this.categories.filter((category) => category.id !== id);
        }
        toastStore.setMessage('Category successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useCategoryStore;
