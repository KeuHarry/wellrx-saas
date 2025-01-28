<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="flex col-span-12 h-12 px-5 justify-end">
      <Button variant="primary" @click="createUpdateProduct" class="mt-3 mr-3"> Save </Button>
      <Button v-if="productId" variant="soft-success" class="mt-3 mr-3"> View Inventory </Button>
      <Button variant="outline-secondary" @click="closeAddProductForm" class="mt-3">
        Cancel
      </Button>
    </div>
    <div class="grid grid-cols-12 gap-3 m-5">
      <div class="col-span-8 p-5 bg-white border rounded-md shadow-sm">
        <h2 class="text-slate-900 pb-5 font-medium">General Information</h2>
        <div class="grid grid-cols-12 gap-4">
          <div class="col-span-10">
            <label for="product-name" class="block text-sm/6 text-gray-600"
              >Product Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.label"
              type="text"
              placeholder="Enter product name"
              name="product-name"
              id="product-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="product-name" class="block text-sm/6 text-gray-600"
              >Type <span class="text-danger">*</span></label
            >
            <div class="flex items-center h-9 gap-6">
              <div v-for="type in productTypes" :key="type.id" class="space-x-2 text-center">
                <input
                  type="radio"
                  :value="type.value"
                  v-model="formData.isPrescription"
                  :id="'id-' + type.id"
                  class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
                <label class="mr-4">{{ type.name }}</label>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <label for="description" class="block text-sm/6 text-gray-600">Description</label>
          <textarea
            v-model="formData.description"
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="description"
            id="description"
          >
          </textarea>
        </div>
        <div class="grid grid-cols-12 gap-2 mt-4">
          <div class="col-span-4">
            <label for="stock-id" class="block text-sm/6 text-gray-600"
              >Stock ID # <span class="text-danger">*</span></label
            >
            <input
              v-model="productCode"
              disabled
              type="text"
              placeholder="Enter stock ID"
              name="stock-id"
              id="stock-id"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="barcode" class="block text-sm/6 text-gray-600">Barcode</label>
            <input
              v-model="formData.barcode"
              type="text"
              placeholder="Enter barcode"
              name="barcode"
              id="barcode"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="cip" class="block text-sm/6 text-gray-600">CIP #</label>
            <input
              v-model="formData.cipCode"
              type="text"
              placeholder="Enter CIP"
              name="cip"
              id="cip"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-12 gap-4 mt-4">
          <div class="col-span-3 flex items-center space-x-2">
            <input
              v-model="formData.isGeneric"
              type="checkbox"
              id="is-generic"
              name="is-generic"
              class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
            <label for="is-generic" class="mr-4">Generic</label>
          </div>
          <div class="col-span-9">
            <label for="generic-product" class="block text-sm/6 text-gray-600"
              >Generic Product</label
            >
            <TomSelect
              v-model="genericProductId"
              :options="{
                placeholder: 'Search generic product',
              }"
              class="w-full h-9 border-gray-300 rounded-md shadow-sm text-sm text-slate-900 sm:text-sm/6"
            >
              <option value="1d">Leonardo DiCaprio</option>
              <option value="2e">Johnny Deep</option>
              <option value="3sas">Robert Downey, Jr</option>
              <option value="4s">Samuel L. Jackson</option>
              <option value="5we">Morgan Freeman</option>
            </TomSelect>
          </div>
        </div>
        <div class="grid grid-cols-1 gap-1 mt-4">
          <label for="label-code" class="block text-sm/6 text-gray-600">Labels</label>
          <TomSelect
            v-model="selectedLabels"
            :options="{
              placeholder: 'Select your labels',
            }"
            class="w-full"
            multiple
          >
            <option value="1">Leonardo DiCaprio</option>
            <option value="2">Johnny Deep</option>
            <option value="3">Robert Downey, Jr</option>
            <option value="4">Samuel L. Jackson</option>
            <option value="5">Morgan Freeman</option>
          </TomSelect>
        </div>
        <div class="flex items-center mt-6 gap-8">
          <div class="flex items-center space-x-2">
            <input
              v-model="formData.isActive"
              type="checkbox"
              id="is-active"
              name="is-active"
              class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
            />
            <label for="is-active">Active</label>
          </div>
          <div class="flex items-center space-x-2">
            <input
              v-model="formData.isDecomposable"
              type="checkbox"
              id="is-decomposable"
              name="is-decomposable"
              class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
            />
            <label for="is-decomposable">Decomposable</label>
          </div>
        </div>
      </div>
      <div class="col-span-4">
        <div class="grid grid-cols-1 p-5 bg-white border rounded-md shadow-sm">
          <h2 class="text-slate-900 pb-5 font-medium">Category</h2>
          <div class="grid grid-cols-1 gap-2">
            <div>
              <label for="product-category" class="block text-sm/6 text-gray-600"
                >Product Category <span class="text-danger">*</span></label
              >
              <ComboBox
                :options="categories"
                @selected="selectCategory"
                :defaultOption="selectedCategory"
                :placeholder="'Select category'"
              />
            </div>
            <div class="mt-4">
              <label for="shelf" class="block text-sm/6 text-gray-600"
                >Shelf <span class="text-danger">*</span></label
              >
              <ComboBox
                :options="shelves"
                @selected="selectShelf"
                :defaultOption="selectedShelf"
                :placeholder="'Select shelf'"
              />
            </div>
          </div>
        </div>
        <div class="grid grid-cols-1 p-5 mt-3 bg-white border rounded-md shadow-sm">
          <h2 class="text-slate-900 pb-5 font-medium">Inventory</h2>
          <div class="grid grid-cols-1 gap-2">
            <div>
              <label for="last-supplier" class="block text-sm/6 text-gray-600">Last Supplier</label>
              <input
                disabled
                type="text"
                placeholder="Last supplier info."
                name="last-supplier"
                id="last-supplier"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="mt-2">
              <label for="last-sale" class="block text-sm/6 text-gray-600">Last Sale</label>
              <input
                disabled
                type="text"
                placeholder="Last sale info."
                name="last-sale"
                id="last-sale"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="mt-2">
              <label for="expiry-date" class="block text-sm/6 text-gray-600"
                >Closest Expiration Date</label
              >
              <input
                disabled
                type="text"
                placeholder="Closest exp. date"
                name="expiry-date"
                id="expiry-date"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="col-span-8 p-5 bg-white border rounded-md shadow-sm">
        <h2 class="text-slate-900 pb-5 font-medium">Pricing</h2>
        <div class="grid grid-cols-12 gap-2">
          <div class="col-span-2">
            <label for="markup" class="block text-sm/6 text-gray-600">Markup (%)</label>
            <input
              v-model="formData.markup"
              type="number"
              placeholder="Enter markup"
              name="markup"
              id="markup"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="tax" class="block text-sm/6 text-gray-600">Tax (%)</label>
            <TomSelect
              v-model="feeId"
              :options="{
                placeholder: 'Select Tax',
              }"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 sm:text-sm/6"
            >
              <option value="1">Leonardo DiCaprio</option>
              <option value="2">Johnny Deep</option>
              <option value="3">Robert Downey, Jr</option>
              <option value="4">Samuel L. Jackson</option>
              <option value="5">Morgan Freeman</option>
            </TomSelect>
          </div>
          <div class="col-span-2">
            <label for="acq-cost" class="block text-sm/6 text-gray-600"
              >Acquisation Cost <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.acqCost"
              type="number"
              placeholder="Enter min quantity"
              name="acq-cost"
              id="acq-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="wholesale-price" class="block text-sm/6 text-gray-600"
              >Wholesale Price <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.wholesaleCost"
              type="number"
              placeholder="Enter max quantity"
              name="wholesale-price"
              id="wholesale-price"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="selling-price" class="block text-sm/6 text-gray-600"
              >Retail Price <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.retailCost"
              type="number"
              placeholder="Enter max quantity"
              name="wholesale-price"
              id="wholesale-price"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="unit-price" class="block text-sm/6 text-gray-600"
              >Unit Price <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.unitCost"
              type="number"
              placeholder="Enter max quantity"
              name="unit-price"
              id="unit-price"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
      </div>
      <div class="col-span-4 p-5 bg-white border rounded-md shadow-sm">
        <h2 class="text-slate-900 pb-5 font-medium">Quantities</h2>
        <div class="grid grid-cols-12 gap-2">
          <div class="col-span-3">
            <label for="pkg-size" class="block text-sm/6 text-gray-600"
              >Package Size <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.pkgSize"
              type="number"
              placeholder="Ex. 1"
              name="pkg-size"
              id="pkg-size"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="qoh" class="block text-sm/6 text-gray-600"
              >QOH <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.qoh"
              disabled
              type="number"
              placeholder="Enter qoh"
              name="qoh"
              id="qoh"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-100 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="min-quantity" class="block text-sm/6 text-gray-600">Min Quantity</label>
            <input
              v-model="formData.minQuantity"
              type="number"
              placeholder="Enter min qty"
              name="min-quantity"
              id="min-quantity"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="max-quantity" class="block text-sm/6 text-gray-600">Max Quantity</label>
            <input
              v-model="formData.maxQuantity"
              type="number"
              placeholder="Enter max qty"
              name="max-quantity"
              id="max-quantity"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  <Notification
    refKey="notificationRef"
    :options="{
      duration: 3000,
    }"
    class="flex flex-col sm:flex-row"
  >
    <Lucide :icon="iconType" class="text-success" />
    <div class="font-medium ml-4 mr-4">{{ message.content }}</div>
  </Notification>
</template>

<script setup lang="ts">
import Lucide from '@/components/Base/Lucide';
import { productTypes } from '@/constants/productType';
import { computed, onMounted, provide, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import TomSelect from '@/components/Base/TomSelect';
import { ProductRequest } from '@/composables/models/ProductRequest';
import Button from '@/components/Base/Button';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useProductStore from '@/stores/product';
import useTenantStore from '@/stores/tenant';
import useShelfStore from '@/stores/shelf';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { Shelf } from '@/composables/models/Shelf';
import ComboBox from '@/components/ComboBox.vue';
import useCategoryStore from '@/stores/category';
import { Option } from '@/composables/models/Option';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';

const router = useRouter();
const route = useRoute();

const genericProductId = ref('');
const shelfId = ref('1');
const productCategoryId = ref('1');
const feeId = ref('');
const selectedLabels = ref([]);
const isSearchable = ref(true);
const selectedShelf = ref<Option>({
  id: 0,
  name: '',
});
const selectedCategory = ref<Option>({
  id: 0,
  name: '',
});
const isLoading = ref(false);

const productId = ref<number | null>(route.params.id ? parseInt(route.params.id.toString()) : null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const formData = ref<ProductRequest>({
  label: '',
  isPrescription: false,
  isGeneric: false,
  genericProductId: null,
  barcode: '',
  cipCode: '',
  acqCost: 0,
  wholesaleCost: 0,
  retailCost: 0,
  unitCost: 0,
  markup: null,
  description: '',
  qoh: 0,
  minQuantity: null,
  maxQuantity: null,
  isDecomposable: false,
  isActive: true,
  pkgSize: 1,
  shelfId: 1,
  productCategoryId: 1,
  feeId: null,
});

// STORE
const tenantStore = useTenantStore();
const shelfStore = useShelfStore();
const categoryStore = useCategoryStore();
const toastStore = useToastStore();
const productStore = useProductStore();

// COMPUTED
const shelves = computed(() => shelfStore.getShelves);
const categories = computed(() => categoryStore.getCategories);
const product = computed(() => productStore.getProduct);
const productCode = computed(() => product.value?.productCode);
const message = computed(() => toastStore.getMessage);
const iconType = computed(() => {
  if (message.value.type === NotificationType.WARNING) {
    return 'AlertCircle';
  }

  if (message.value.type === NotificationType.ERROR) {
    return 'XCircle';
  }

  return 'CheckCircle';
});

// METHOD
const tenantVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const closeAddProductForm = () => {
  router.push({
    name: 'product-list',
    params: { identifier: route.params.identifier },
  });
};

const createUpdateProduct = async () => {
  if (productId.value) {
    await productStore.updateProductApi(productId.value, formData.value);
  } else {
    await productStore.createProductApi(formData.value);
  }
  basicNonStickyNotificationToggle();
};

const getProductData = async () => {
  if (productId.value) {
    await productStore.getProductApi(productId.value);
    await setProductForm();
  }
};

const setProductForm = async () => {
  formData.value.label = product.value?.label ?? '';
  formData.value.isPrescription = product.value?.isPrescription ?? false;
  formData.value.description = product.value?.description ?? '';
  formData.value.barcode = product.value?.barcode ?? '';
  formData.value.cipCode = product.value?.cipCode ?? '';
  formData.value.isGeneric = product.value?.isGeneric ?? false;
  formData.value.markup = product.value?.markup ?? null;
  formData.value.acqCost = product.value?.acqCost ?? 0;
  formData.value.retailCost = product.value?.retailCost ?? 0;
  formData.value.wholesaleCost = product.value?.wholesaleCost ?? 0;
  formData.value.unitCost = product.value?.unitCost ?? 0;
  formData.value.isActive = product.value?.isActive ?? true;
  formData.value.minQuantity = product.value?.minQuantity ?? null;
  formData.value.maxQuantity = product.value?.maxQuantity ?? null;
  formData.value.qoh = product.value?.qoh ?? 0;

  selectedShelf.value = product.value?.shelf ?? { id: 0, name: '' };
  selectedCategory.value = product.value?.category ?? { id: 0, name: '' };
};

const getShelvesData = async (query: string = '') => {
  await shelfStore.getShelvesApi({
    search: query,
    sortBy: NAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const getCategoriesData = async (query: string = '') => {
  await categoryStore.getCategoriesApi({
    search: query,
    sortBy: NAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const searchShelf = async (query: string) => {
  if (query.length === 0 || query.length >= 3) {
    await getShelvesData(query);
  }
};

const selectShelf = (value: { id: number; name: string }) => {
  formData.value.shelfId = value.id;
};

const selectCategory = (value: { id: number; name: string }) => {
  formData.value.productCategoryId = value.id;
};

onMounted(async () => {
  await tenantVerification();
  await getProductData();
  await getShelvesData();
  await getCategoriesData();
});
</script>
