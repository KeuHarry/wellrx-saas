import { SearchParams } from '@/composables/models/SearchParams';
import { LABEL } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Product } from '@/composables/models/Product';
import { ProductRequest } from '@/composables/models/ProductRequest';
import useCreateProduct from '@/composables/api/products/useCreateProduct';
import useGetProducts from '@/composables/api/products/useGetProducts';
import useUpdateProduct from '@/composables/api/products/useUpdateProduct';
import useDeleteProduct from '@/composables/api/products/useDeleteProduct';
import useGetProduct from '@/composables/api/products/useGetProduct';
import { InStockProduct } from '@/composables/models/InStockProduct';
import useGetInStockProducts from '@/composables/api/products/useGetInStockProducts';

type ProductState = {
  products: Product[];
  inStockProducts: InStockProduct[];
  productTotal: number;
  product: Product | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useProductStore = defineStore('product-store', {
  state: (): ProductState => ({
    products: [],
    inStockProducts: [],
    productTotal: 0,
    product: null,
    searchParams: {
      search: '',
      sortBy: LABEL,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
      shelfId: null,
    },
    isPending: false,
  }),

  getters: {
    getProducts: (state) => state.products,
    getProduct: (state) => state.product,
    getProductTotal: (state) => state.productTotal,
    getInStockProducts: (state) => state.inStockProducts,
  },

  actions: {
    async getProductsApi(searchParams: SearchParams | null = null) {
      const getProducts = useGetProducts(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getProducts();
        this.products = response.data ?? [];
        this.productTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createProductApi(productRequest: ProductRequest) {
      const createProduct = useCreateProduct();
      try {
        this.isPending = true;
        const response = await createProduct(productRequest);
        this.product = response.data;
        toastStore.setMessage('Product successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateProductApi(id: number, productRequest: ProductRequest) {
      const udpateProduct = useUpdateProduct();
      try {
        this.isPending = true;
        const response = await udpateProduct(id, productRequest);
        toastStore.setMessage('Product successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteProductApi(id: number) {
      const deleteProduct = useDeleteProduct();
      try {
        this.isPending = true;
        const response = await deleteProduct(id);
        if (response.success) {
          this.products = this.products.filter((product) => product.id !== id);
        }
        toastStore.setMessage('Product successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getProductApi(id: number) {
      const getProduct = useGetProduct();
      try {
        this.isPending = true;
        const response = await getProduct(id);
        if (response.success) {
          this.product = response.data;
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getInStockProductsApi(searchParams: SearchParams | null = null) {
      const getProducts = useGetInStockProducts(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getProducts();
        this.inStockProducts = response.data ?? [];
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useProductStore;
