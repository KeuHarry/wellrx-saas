<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="flex col-span-12 h-14 px-5 justify-between">
      <div class="flex items-center">
        <h2 class="text-lg font-medium">{{ pageTitle }}</h2>
      </div>
      <div class="flex items-center">
        <Button
          :disabled="!canAddInventory"
          variant="primary"
          @click="addInventory"
          class="mr-3 disabled:cursor-not-allowed"
          >Save</Button
        >
        <Button variant="outline-secondary" @click="closeAddRemoveInventoryForm">Cancel</Button>
      </div>
    </div>
    <div class="grid grid-cols-12 gap-3 m-5">
      <div class="flex col-span-12 p-5 bg-white border rounded-md shadow-sm justify-end">
        <h2 class="text-slate-900 font-medium">Invoice Total: {{ formatPrice(invoiceTotal) }}</h2>
      </div>
      <div class="col-span-12 p-5 bg-white border rounded-md shadow-sm justify-end">
        <label for="description" class="block text-sm/6 text-gray-600">Comment</label>
        <textarea
          v-model="formData.reason"
          class="block w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          name="description"
          id="description"
        >
        </textarea>
      </div>
      <div class="col-span-12 bg-white border rounded-md shadow-sm">
        <div class="flex col-span-12 gap-2 p-5">
          <div class="w-[60%]">
            <label for="product-name" class="block text-sm/6 text-gray-600"
              >Product <span class="text-danger">*</span></label
            >
            <ComboBox
              :options="products"
              @search="searchProduct"
              @selected="selectProduct"
              :defaultOption="selectedProduct"
              placeholder="Select product"
            />
          </div>
          <div class="w-[9%]">
            <label for="qty" class="block text-sm/6 text-gray-600"
              >Qty <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.quantity"
              @input="updateSubTotal"
              type="number"
              min="1"
              placeholder="Ex. 0"
              name="qty"
              id="qty"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="acq-cost" class="block text-sm/6 text-gray-600">Acq. Cost</label>
            <input
              v-model="formData.acqCost"
              @input="updateSubTotal"
              type="number"
              placeholder="Enter acq cost"
              name="acq-cost"
              id="acq-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="wholesale-cost" class="block text-sm/6 text-gray-600">wholesale Cost</label>
            <input
              v-model="formData.wholesaleCost"
              type="number"
              placeholder="Enter wholesale cost"
              name="wholesale-cost"
              id="wholesale-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="retail-cost" class="block text-sm/6 text-gray-600">Retail Cost</label>
            <input
              v-model="formData.retailCost"
              type="number"
              placeholder="Enter retail cost"
              name="retail-cost"
              id="retail-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[28%]">
            <label for="supplier" class="block text-sm/6 text-gray-600"
              >Supplier <span class="text-danger">*</span></label
            >
            <ComboBox
              :options="suppliers"
              @search="searchSupplier"
              @selected="selectSupplier"
              placeholder="Select supplier"
            />
          </div>
          <div class="w-[11%]">
            <label for="retail-cost" class="block text-sm/6 text-gray-600"
              >Expiry Date <span class="text-danger">*</span></label
            >
            <Litepicker
              v-model="formData.expiryDate"
              :options="dateOptions"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="flex col-span-1 items-end justify-end">
            <Button variant="primary" class="w-10 h-10 px-0 rounded-full" @click="addProduct">
              <Lucide icon="Plus" class="w-6 h-6" />
            </Button>
          </div>
        </div>
        <div class="flex col-span-12 gap-4 px-5 pb-5">
          <p>
            QOH: <span class="font-medium mr-3">{{ productQoh }}</span> Size:
            <span class="font-medium mr-3">{{ productPkgSize }}</span> Qty:
            <span class="font-medium mr-3">{{ formData.quantity }}</span> Subtotal:
            <span class="font-medium">{{ formatPrice(formData.subTotal) }}</span>
          </p>
        </div>
      </div>
      <div class="col-span-12 p-5 bg-white border rounded-md shadow-sm">
        <Table>
          <Table.Thead variant="dark">
            <Table.Tr>
              <Table.Th class="whitespace-nowrap">Drug Name</Table.Th>
              <Table.Th class="whitespace-nowrap">Qty</Table.Th>
              <Table.Th class="whitespace-nowrap">QOH</Table.Th>
              <Table.Th class="whitespace-nowrap">Acq. Cost</Table.Th>
              <Table.Th class="whitespace-nowrap">Wholesale Cost</Table.Th>
              <Table.Th class="whitespace-nowrap">Retail Cost</Table.Th>
              <Table.Th class="whitespace-nowrap">Sub Total</Table.Th>
              <Table.Th class="whitespace-nowrap">Supplier</Table.Th>
              <Table.Th class="whitespace-nowrap">Expiry Date</Table.Th>
              <Table.Th class="whitespace-nowrap"></Table.Th>
            </Table.Tr>
          </Table.Thead>
          <Table.Tbody>
            <Table.Tr v-for="(inventoryData, ikey) in inventoriesTable" :key="ikey">
              <Table.Td>{{ inventoryData.productName }}</Table.Td>
              <Table.Td>{{ inventoryData.quantity }}</Table.Td>
              <Table.Td>{{ inventoryData.qoh }}</Table.Td>
              <Table.Td>{{ inventoryData.acqCost }}</Table.Td>
              <Table.Td>{{ inventoryData.wholesaleCost }}</Table.Td>
              <Table.Td>{{ inventoryData.retailCost }}</Table.Td>
              <Table.Td>{{ inventoryData.subTotal }}</Table.Td>
              <Table.Td>{{ inventoryData.supplierName }}</Table.Td>
              <Table.Td>{{ inventoryData.expiryDate }}</Table.Td>
              <Table.Td>
                <a
                  @click="removeEntry(inventoryData)"
                  class="flex items-center text-danger cursor-pointer"
                >
                  <Lucide icon="XIcon" class="w-4 h-4" />
                </a>
              </Table.Td>
            </Table.Tr>
            <Table.Tr v-if="inventoriesTable.length === 0">
              <Table.Td :colspan="10">
                <div class="grid grid-cols-1 p-2 text-center">
                  <span class="text-slate-400">No Selection</span>
                </div>
              </Table.Td>
            </Table.Tr>
          </Table.Tbody>
        </Table>
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
import { computed, onMounted, provide, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Table from '@/components/Base/Table';
import TomSelect from '@/components/Base/TomSelect';
import { ProductRequest } from '@/composables/models/ProductRequest';
import Button from '@/components/Base/Button';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useProductStore from '@/stores/product';
import useTenantStore from '@/stores/tenant';
import { LABEL, NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import ComboBox from '@/components/ComboBox.vue';
import { capitalizeFirstLetter, formatPrice } from '@/utils/helper';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import { Product } from '@/composables/models/Product';
import useSupplierStore from '@/stores/supplier';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';
import { InventoryRequest } from '@/composables/models/InventoryRequest';
import InventoryType from '@/enums/inventoryType';
import dayjs from 'dayjs';
import { Supplier } from '@/composables/models/Supplier';
import NumericInput from '@/components/NumericInput.vue';
import _ from 'lodash';
import { Option } from '@/composables/models/Option';
import useInventoryStore from '@/stores/inventory';
import { toRaw } from 'vue';

const router = useRouter();
const route = useRoute();

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});
const productQoh = ref(0);
const productPkgSize = ref(1);
const selectedProduct = ref<Option>({
  id: 0,
  name: '',
});
const dateOptions = ref({
  showWeekNumbers: true,
  format: dateFormat,
  dropdowns: {
    minYear: 1900,
    maxYear: null,
    months: true,
    years: true,
  },
});

const productName = ref<string>('');
const supplierName = ref<string>('');
const invoiceTotal = ref<number>(0);
const inventoriesTable = ref<
  {
    id: number;
    productName: string;
    quantity: number;
    qoh: number;
    acqCost: number;
    wholesaleCost: number;
    retailCost: number;
    subTotal: number;
    supplierName: string;
    expiryDate: string;
  }[]
>([]);
const inventoriesData = ref<InventoryRequest[]>([]);
const formData = ref<InventoryRequest>({
  productId: 0,
  quantity: 1,
  type: InventoryType.IN,
  expiryDate: dayjs().format(dateFormat),
  acqCost: 0,
  wholesaleCost: 0,
  retailCost: 0,
  unitCost: 0,
  supplierId: 0,
  reason: '',
  subTotal: 0,
  total: 0,
});

// STORE
const tenantStore = useTenantStore();
const toastStore = useToastStore();
const productStore = useProductStore();
const supplierStore = useSupplierStore();
const inventoryStore = useInventoryStore();

// COMPUTED
const pageTitle = computed(() => `Inventory ${capitalizeFirstLetter(route.params.type as string)}`);
const products = computed(() =>
  productStore.getProducts.map((product) => {
    return {
      ...product,
      name: `${product.label} (${product.qoh})`,
    };
  }),
);
const suppliers = computed(() => supplierStore.getSuppliers);
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

const canAddInventory = computed(() => inventoriesTable.value.length > 0);

// METHOD
const tenantVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const closeAddRemoveInventoryForm = () => {
  router.push({
    name: 'inventory',
    params: { identifier: route.params.identifier },
  });
};

const getProductsData = async (searchString: string = '') => {
  await productStore.getProductsApi({
    search: searchString,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const getSuppliersData = async (searchString: string = '') => {
  await supplierStore.getSuppliersApi({
    search: searchString,
    sortBy: NAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const searchProduct = async (searchFilter: string) => {
  await getProductsData(searchFilter);
};

const searchSupplier = async (searchFilter: string) => {
  await getSuppliersData(searchFilter);
};

const selectProduct = (product: any) => {
  formData.value.productId = product.id;
  productName.value = product.name;
  productQoh.value = product.qoh;
  productPkgSize.value = product.pkgSize;
  formData.value.acqCost = product.acqCost;
  formData.value.wholesaleCost = product.wholesaleCost;
  formData.value.retailCost = product.retailCost;
  formData.value.unitCost = product.unitCost;
  formData.value.subTotal = formData.value.acqCost * formData.value.quantity;
};

const selectSupplier = (supplier: Supplier) => {
  formData.value.supplierId = supplier.id;
  supplierName.value = supplier.name;
};

const updateSubTotal = () => {
  formData.value.subTotal = formData.value.acqCost * formData.value.quantity;
};

const resetFormData = () => {
  formData.value.productId = 0;
  formData.value.quantity = 1;
  formData.value.type = InventoryType.IN;
  formData.value.expiryDate = dayjs().format(dateFormat);
  formData.value.acqCost = 0;
  formData.value.wholesaleCost = 0;
  formData.value.retailCost = 0;
  formData.value.unitCost = 0;
  formData.value.subTotal = 0;
  formData.value.total = 0;
  selectedProduct.value = { id: 0, name: '' };
};

const addProduct = () => {
  const rawFormData = toRaw(formData.value);
  inventoriesData.value.push({ ...rawFormData });
  inventoriesTable.value.push({
    id: rawFormData.productId,
    productName: productName.value,
    quantity: rawFormData.quantity,
    qoh: productQoh.value,
    acqCost: rawFormData.acqCost,
    wholesaleCost: rawFormData.wholesaleCost,
    retailCost: rawFormData.retailCost,
    subTotal: rawFormData.subTotal,
    supplierName: supplierName.value,
    expiryDate: rawFormData.expiryDate,
  });
  invoiceTotal.value += rawFormData.subTotal;
  resetFormData();
};

const removeEntry = (inventoryEntry: {
  id: number;
  productName: string;
  quantity: number;
  qoh: number;
  acqCost: number;
  wholesaleCost: number;
  retailCost: number;
  subTotal: number;
  supplierName: string;
  expiryDate: string;
}) => {
  inventoriesTable.value = inventoriesTable.value.filter(
    (inventory) => inventory.id !== inventoryEntry.id,
  );
  invoiceTotal.value -= inventoryEntry.subTotal;
};

const addInventory = async () => {
  await inventoryStore.createInventoryApi({ inventories: inventoriesData.value });
  basicNonStickyNotificationToggle();
};

onMounted(async () => {
  await tenantVerification();
  await getProductsData();
  await getSuppliersData();
});
</script>
