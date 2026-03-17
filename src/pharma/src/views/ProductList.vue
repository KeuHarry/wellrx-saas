<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Products</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="relative z-10 flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openAddEditProduct()" variant="primary" class="mr-2 shadow-md">
        Add Product
      </Button>
      <Menu>
        <Menu.Button :as="Button" class="px-2 !box">
          <span class="flex items-center justify-center w-5 h-5">
            <Lucide icon="Plus" class="w-4 h-4" />
          </span>
        </Menu.Button>
        <Menu.Items class="w-40">
          <Menu.Item @click="exportProducts">
            <Lucide icon="FileText" class="w-4 h-4 mr-2" /> Export to Excel
          </Menu.Item>
        </Menu.Items>
      </Menu>
      <div class="hidden mx-auto md:block text-slate-500"></div>
      <div class="mr-3 h-9">
        <select
          v-model="shelfId"
          class="h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 w-48"
          name="shelf-filter"
          id="shelf-filter"
          @change="getProductsData"
        >
          <option :value="null">All shelves</option>
          <option
            v-for="shelf in shelves"
            :key="shelf.id"
            :value="shelf.id"
          >
            {{ shelf.name }}
          </option>
        </select>
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchProduct"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by code, name or barcode..."
          />
          <Lucide icon="Search" class="absolute inset-y-0 right-0 w-4 h-4 my-auto mr-3" />
        </div>
      </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="relative z-0 col-span-12">
      <ProductTable
        :products="products"
        @edit="openAddEditProduct"
        @delete="openDeleteProductModal"
      />
    </div>
    <!-- END: Data List -->

    <PaginationComponent
      :totalItems="productTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteProduct" />
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
import * as _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import { Menu } from '@/components/Base/Headless';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import { useRoute, useRouter } from 'vue-router';
import useProductStore from '@/stores/product';
import { Product } from '@/composables/models/Product';
import useTenantStore from '@/stores/tenant';
import useShelfStore from '@/stores/shelf';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import { NAME } from '@/constants/properties';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';
import SortOrder from '@/enums/sortOrder';
import { LABEL } from '@/constants/properties';
import ProductTable from '@/components/ProductTable.vue';
import useExportProducts from '@/composables/api/products/useExportProducts';

const deleteModalRef = ref<InstanceType<typeof DeleteModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const router = useRouter();
const route = useRoute();
const search = ref('');
const currentPage = ref(1);
const paginate = ref(10);
const colspan = ref(0);

// STORE
const tenantStore = useTenantStore();
const productStore = useProductStore();
const shelfStore = useShelfStore();
const toastStore = useToastStore();

const shelfId = ref<number | null>(null);

// COMPUTED
const products = computed(() => productStore.getProducts);
const shelves = computed(() => shelfStore.getShelves);
const productTotal = computed(() => productStore.getProductTotal);
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

const startProductCount = computed(() => (products.value.length > 0 ? 1 : 0));
const hasProduct = computed(() => products.value.length > 0);

//METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getColspan = () => {
  const thElements = document.querySelectorAll('thead th');
  colspan.value = thElements.length;
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getProductsData = async () => {
  await productStore.getProductsApi({
    search: search.value,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    shelfId: shelfId.value,
  });
};

const searchProduct = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getProductsData();
  }
};

const handlePageChange = async (page: number) => {
  currentPage.value = page;
  await getProductsData();
};

const openAddEditProduct = (productId: number | null = null) => {
  if (productId) {
    router.push({
      name: 'edit-product',
      params: { identifier: route.params.identifier, id: productId },
    });
  } else {
    router.push({
      name: 'add-product',
      params: { identifier: route.params.identifier },
    });
  }
};

const openDeleteProductModal = (product: Product) => {
  deleteModalRef.value?.open(
    { id: product.id, reference: product.productCode },
    'Do you really want to delete the product',
  );
};

const deleteProduct = async (id: number) => {
  await productStore.deleteProductApi(id);
};

const exportProductsFn = useExportProducts();
const exportProducts = async () => {
  try {
    await exportProductsFn();
    toastStore.setMessage('Products exported successfully.');
    basicNonStickyNotificationToggle();
  } catch {
    toastStore.setMessage('Export failed.', NotificationType.ERROR);
    basicNonStickyNotificationToggle();
  }
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await shelfStore.getShelvesApi({
    search: '',
    sortBy: NAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage * 10,
  });
  await getProductsData();
});
</script>
