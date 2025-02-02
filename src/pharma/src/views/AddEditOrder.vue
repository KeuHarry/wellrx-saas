<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="flex col-span-12 h-14 px-5 justify-between">
      <div class="flex items-center">
        <h2 class="text-lg font-medium">Order Details {{ invoiceCode }}</h2>
        <span
          v-if="order"
          class="border rounded-full text-xs px-4 py-1 ml-1 bg-opacity-20 border-opacity-5"
          :class="{
            'bg-green-600 border-green-600 text-green-600': isCompleted,
            'bg-blue-600 border-blue-600 text-blue-600': isInProgress,
          }"
          >{{ orderStatus }}
        </span>
      </div>
      <div class="flex items-center">
        <Button
          v-if="!isCompleted"
          variant="primary"
          @click="addOrder"
          :disabled="!canSaveOrder"
          class="mr-3"
          >Save</Button
        >
        <Button
          v-if="!isCompleted"
          @click="openCompleteModal"
          :disabled="!canComplete"
          class="hover:bg-teal-600/15 bg-teal-600 hover:border-teal-600/10 border-teal-600 text-teal-600 bg-opacity-20 border-opacity-5 mr-3"
          >Complete</Button
        >
        <Button variant="outline-secondary" @click="closeAddEditOrderForm">Cancel</Button>
      </div>
    </div>
    <div class="grid grid-cols-12 gap-3 m-5">
      <div class="col-span-12 p-5 bg-white border rounded-md shadow-sm space-y-7">
        <div v-if="!order" class="flex items-end grid-cols-12 gap-2">
          <span class="font-medium text-slate-900 mr-4">Expected:</span>
          <div v-if="!isExpected" class="col-span-2">
            <label for="invoice" class="block text-sm/6 text-gray-600">Invoice #</label>
            <input
              v-model="orderForm.invoiceCode"
              type="text"
              placeholder="Enter invoice #"
              name="invoice"
              id="invoice"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="sub-total" class="block text-sm/6 text-gray-600">Sub Total (No Tax)</label>
            <input
              v-model="expectedSubTotal"
              type="number"
              placeholder="Enter sub total"
              name="sub-total"
              id="sub-total"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isExpected"
            />
          </div>
          <div class="col-span-2">
            <label for="tax" class="block text-sm/6 text-gray-600">Tax</label>
            <input
              v-model="expectedTax"
              type="number"
              placeholder="Enter tax"
              name="tax"
              id="tax"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isExpected"
            />
          </div>
          <div class="col-span-2">
            <label for="charges" class="block text-sm/6 text-gray-600">Charges</label>
            <input
              v-model="expectedCharges"
              type="number"
              placeholder="Enter charges"
              name="charges"
              id="charges"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isExpected"
            />
          </div>
          <div class="col-span-2">
            <label for="total" class="block text-sm/6 text-gray-600">Invoice Total</label>
            <input
              v-model="expectedTotal"
              type="number"
              placeholder="Enter total"
              name="total"
              id="total"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isExpected"
            />
          </div>
          <Button
            :disabled="!canSaveExpected"
            variant="primary"
            @click="saveExpectedOrder"
            class="h-9 items-end mr-3"
          >
            <Lucide :icon="saveExpectedIcon" />
          </Button>
        </div>
        <div v-if="isExpected || order" class="flex items-end grid-cols-12 gap-2">
          <span class="font-medium text-slate-900 mr-4">Actual:</span>
          <div class="w-[18%]">
            <label for="supplier" class="block text-sm/6 text-gray-600"
              >Supplier <span class="text-danger">*</span></label
            >
            <ComboBox
              :options="suppliers"
              @search="searchSupplier"
              @selected="selectSupplier"
              :defaultOption="selectedSupplier"
              placeholder="Select supplier"
              :disabled="isCompleted"
              :classes="'disabled:cursor-not-allowed'"
            />
          </div>
          <div class="col-span-2">
            <label for="retail-cost" class="block text-sm/6 text-gray-600"
              >Invoice Date <span class="text-danger">*</span></label
            >
            <Litepicker
              v-model="orderForm.invoiceDate"
              :options="dateOptions"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isCompleted"
            />
          </div>
          <div class="col-span-2">
            <label for="sub-total" class="block text-sm/6 text-gray-600">Sub Total (No Tax)</label>
            <input
              v-model="orderSubTotal"
              type="text"
              placeholder="Enter sub total"
              name="sub-total"
              id="sub-total"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="tax" class="block text-sm/6 text-gray-600">Tax</label>
            <input
              v-model="taxTotal"
              type="text"
              placeholder="Enter tax"
              name="tax"
              id="tax"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="charges" class="block text-sm/6 text-gray-600">Charges</label>
            <input
              v-model="chargeTotal"
              type="text"
              placeholder="Enter charges"
              name="charges"
              id="charges"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="total" class="block text-sm/6 text-gray-600">Invoice Total</label>
            <input
              v-model="invoiceTotal"
              type="text"
              placeholder="Enter total"
              name="total"
              id="total"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
        </div>
      </div>
      <div v-if="!isCompleted" class="col-span-12 bg-white border rounded-md shadow-sm">
        <div class="px-5 pt-5">
          <h2 class="text-lg font-light">Add Product</h2>
        </div>
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
              >Packages <span class="text-danger">*</span></label
            >
            <input
              v-model="orderItemForm.quantity"
              @input="updatePrice"
              type="number"
              min="1"
              placeholder="Ex. 0"
              name="qty"
              id="qty"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="wholesale-cost" class="block text-sm/6 text-gray-600">wholesale Cost</label>
            <input
              v-model="orderItemForm.wholesaleCost"
              type="number"
              placeholder="Enter wholesale cost"
              name="wholesale-cost"
              id="wholesale-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="acq-cost" class="block text-sm/6 text-gray-600">Markup (%)</label>
            <input
              v-model="orderItemForm.markup"
              type="number"
              placeholder="Enter markup"
              name="acq-cost"
              id="acq-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="retail-cost" class="block text-sm/6 text-gray-600">Retail Cost</label>
            <input
              v-model="orderItemForm.retailCost"
              type="number"
              placeholder="Enter retail cost"
              name="retail-cost"
              id="retail-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[11%]">
            <label for="retail-cost" class="block text-sm/6 text-gray-600"
              >Expiry Date <span class="text-danger">*</span></label
            >
            <Litepicker
              v-model="orderItemForm.expiryDate"
              :options="dateOptions"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="item-tax" class="block text-sm/6 text-gray-600">Tax (%)</label>
            <input
              v-model="taxPercentage"
              @input="updateOrderItemTotal"
              type="number"
              placeholder="Enter Taxt"
              name="item-tax"
              id="item-tax"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="flex col-span-1 items-end justify-end">
            <Button
              @click="addProduct"
              :disabled="!canAddProduct"
              variant="primary"
              class="w-9 h-9 px-0 rounded-full"
            >
              <Lucide icon="Plus" class="w-5 h-5" />
            </Button>
          </div>
        </div>
        <div class="grid grid-cols-12 gap-2 p-5 pt-0">
          <div class="col-span-1">
            <label for="discount-type" class="block text-sm/6 text-gray-600">Discount Type</label>
            <select
              v-model="discountType"
              @change="updateOrderItemTotal"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm"
              name="discount-type"
              id="discount-type"
            >
              <option
                v-for="(discountType, ikey) in discountTypeList"
                :key="ikey"
                :value="discountType.id"
              >
                {{ discountType.name }}
              </option>
            </select>
          </div>
          <div class="col-span-1">
            <label for="discount" class="block text-sm/6 text-gray-600">Discount</label>
            <input
              v-model="orderItemDiscount"
              @input="updateOrderItemTotal"
              type="number"
              placeholder="Enter Taxt"
              name="discount"
              id="discount"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="flex col-span-12 gap-4 px-5 pb-5">
          <p>
            QOH: <span class="font-medium mr-3">{{ productQoh }}</span> Size:
            <span class="font-medium mr-3">{{ productPkgSize }}</span> Price:
            <span class="font-medium mr-3">{{ formatPrice(orderItemForm.price) }}</span> Total:
            <span class="font-medium">{{ formatPrice(orderItemTotal) }}</span>
          </p>
        </div>
      </div>
      <div class="col-span-12 p-5 bg-white border rounded-md shadow-sm">
        <Table>
          <Table.Thead variant="dark">
            <Table.Tr>
              <Table.Th class="whitespace-nowrap">Product</Table.Th>
              <Table.Th class="whitespace-nowrap">Pkgs</Table.Th>
              <Table.Th class="whitespace-nowrap">Wholesale Cost</Table.Th>
              <Table.Th class="whitespace-nowrap">Retail Cost</Table.Th>
              <Table.Th class="whitespace-nowrap">Amount</Table.Th>
              <Table.Th class="whitespace-nowrap">Discount</Table.Th>
              <Table.Th class="whitespace-nowrap">Tax</Table.Th>
              <Table.Th class="whitespace-nowrap">Total</Table.Th>
              <Table.Th class="whitespace-nowrap">Expiry Date</Table.Th>
              <Table.Th v-if="!isCompleted" class="whitespace-nowrap"></Table.Th>
            </Table.Tr>
          </Table.Thead>
          <Table.Tbody>
            <Table.Tr v-for="(inventoryData, ikey) in orderItemsTable" :key="ikey">
              <Table.Td>{{ inventoryData.productName }}</Table.Td>
              <Table.Td>{{ inventoryData.quantity }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.wholesaleCost) }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.retailCost) }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.price) }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.discount) }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.tax) }}</Table.Td>
              <Table.Td>{{ formatPrice(inventoryData.subTotal) }}</Table.Td>
              <Table.Td>{{ inventoryData.expiryDate }}</Table.Td>
              <Table.Td v-if="!isCompleted">
                <a
                  @click="removeEntry(inventoryData)"
                  class="flex items-center text-danger cursor-pointer"
                >
                  <Lucide icon="XIcon" class="w-4 h-4" />
                </a>
              </Table.Td>
            </Table.Tr>
            <Table.Tr v-if="orderItemsTable.length === 0">
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
  <ConfirmModal
    ref="confirmModal"
    :modal-title="modalTitle"
    :content="content"
    @submit="completeOrder"
  />
</template>

<script setup lang="ts">
import Lucide from '@/components/Base/Lucide';
import { computed, onMounted, provide, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Table from '@/components/Base/Table';
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
import { formatPrice, getStatus } from '@/utils/helper';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import useSupplierStore from '@/stores/supplier';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';
import dayjs from 'dayjs';
import { Supplier } from '@/composables/models/Supplier';
import _ from 'lodash';
import { Option } from '@/composables/models/Option';
import { toRaw } from 'vue';
import { OrderRequest } from '@/composables/models/OrderRequest';
import OrderStatus from '@/enums/orderStatus';
import { OrderItemRequest } from '@/composables/models/OrderItemRequest';
import { discountTypeList } from '@/constants/discountTypes';
import { DiscountType } from '@/enums/discountType';
import useOrderStore from '@/stores/order';
import ConfirmModal from '@/components/Modals/ConfirmModal.vue';

const router = useRouter();
const route = useRoute();

const confirmModal = ref<InstanceType<typeof ConfirmModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});
const productQoh = ref<number>(0);
const productPkgSize = ref<number>(0);
const orderItemTotal = ref<number>(0);
const selectedProduct = ref<Option>({
  id: 0,
  name: '',
});
const selectedSupplier = ref<Option>({
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
const orderItemsTable = ref<
  {
    id: number;
    productName: string;
    quantity: number;
    wholesaleCost: number;
    retailCost: number;
    price: number;
    discount: number;
    tax: number;
    subTotal: number;
    expiryDate: string;
    markup: number;
  }[]
>([]);
const orderItems = ref<OrderItemRequest[]>([]);
const orderItemForm = ref<OrderItemRequest>({
  productId: 0,
  quantity: 0,
  expiryDate: dayjs().format(dateFormat),
  price: 0,
  discount: 0,
  tax: 0,
  wholesaleCost: 0,
  retailCost: 0,
  markup: 0,
});

const orderForm = ref<OrderRequest>({
  invoiceCode: '',
  supplierId: 0,
  invoiceDate: dayjs().format(dateFormat),
  status: OrderStatus.IN_PROGRESS,
  subTotal: 0,
  taxTotal: 0,
  total: 0,
  charges: 0,
  comment: '',
  orderItems: [],
});
const discountType = ref<number | null>(1);
const orderItemDiscount = ref<number>(0);
const taxPercentage = ref<number>(0);
const isExpected = ref<boolean>(false);

const expectedSubTotal = ref<number>(0);
const expectedTax = ref<number>(0);
const expectedCharges = ref<number>(0);
const expectedTotal = ref<number>(0);
const modalTitle = ref('Complete Order');
const content = ref('Do you really want to complete the order ?');

// STORE
const tenantStore = useTenantStore();
const toastStore = useToastStore();
const productStore = useProductStore();
const supplierStore = useSupplierStore();
const orderStore = useOrderStore();

// COMPUTED
const saveExpectedIcon = computed(() => (isExpected.value ? 'Edit2' : 'SaveAll'));
const invoiceCode = computed(
  () => `${orderForm.value.invoiceCode.length > 0 ? `- #${orderForm.value.invoiceCode}` : ''}`,
);
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
const canSaveExpected = computed(() => orderForm.value.invoiceCode.length > 0);
const canSaveOrder = computed(
  () => canSaveExpected && orderItemsTable.value.length > 0 && orderForm.value.supplierId > 0,
);
const canAddProduct = computed(() => canSaveExpected && orderItemForm.value.productId > 0);
const orderId = computed(() => (route.params.id ? parseInt(route.params.id.toString()) : null));
const order = computed(() => (orderId.value ? orderStore.getOrder : null));

const invoiceTotal = computed(() => formatPrice(orderForm.value.total));
const orderSubTotal = computed(() => formatPrice(orderForm.value.subTotal));
const chargeTotal = computed(() => formatPrice(orderForm.value.charges));
const taxTotal = computed(() => formatPrice(orderForm.value.taxTotal));

const canComplete = computed(
  () => order.value?.status === OrderStatus.IN_PROGRESS && order.value.id,
);
const orderStatus = computed(() => (order.value ? getStatus(order.value?.status) : ''));
const isCompleted = computed(() => order.value?.status === OrderStatus.COMPLETED);
const isInProgress = computed(() => order.value?.status === OrderStatus.IN_PROGRESS);

// METHOD
const tenantVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const closeAddEditOrderForm = () => {
  router.push({
    name: 'order-list',
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
  orderItemForm.value.productId = product.id;
  productName.value = product.name;
  productQoh.value = product.qoh;
  productPkgSize.value = product.pkgSize;
  orderItemForm.value.wholesaleCost = product.wholesaleCost;
  orderItemForm.value.retailCost = product.retailCost;
  orderItemForm.value.markup = product.markup;
  orderItemForm.value.price = orderItemForm.value.wholesaleCost * orderItemForm.value.quantity;
};

const selectSupplier = (supplier: Supplier) => {
  orderForm.value.supplierId = supplier.id;
};

const updatePrice = async () => {
  orderItemForm.value.price = orderItemForm.value.wholesaleCost * orderItemForm.value.quantity;
  await updateOrderItemTotal();
};

const updateOrderItemTotal = async () => {
  switch (discountType.value) {
    case DiscountType.AMOUNT:
      orderItemForm.value.discount = orderItemDiscount.value;
      break;
    case DiscountType.PERCENTAGE:
      orderItemForm.value.discount = orderItemForm.value.price * (orderItemDiscount.value / 100);
      break;
    case DiscountType.QUANTITY:
      orderItemForm.value.discount =
        (orderItemForm.value.price * orderItemDiscount.value) / orderItemForm.value.quantity;
      break;
    default:
      break;
  }

  orderItemForm.value.tax = orderItemForm.value.price * (taxPercentage.value / 100);
  orderItemTotal.value =
    orderItemForm.value.price - orderItemForm.value.discount + orderItemForm.value.tax;
};

const resetFormData = () => {
  orderItemForm.value.productId = 0;
  orderItemForm.value.quantity = 0;
  orderItemForm.value.expiryDate = dayjs().format(dateFormat);
  orderItemForm.value.price = 0;
  orderItemForm.value.discount = 0;
  orderItemForm.value.wholesaleCost = 0;
  orderItemForm.value.retailCost = 0;
  orderItemForm.value.tax = 0;
  orderItemForm.value.markup = 0;
  orderItemDiscount.value = 0;
  productQoh.value = 0;
  productPkgSize.value = 0;
  selectedProduct.value = { id: 0, name: '' };
};

const addProduct = () => {
  const rawFormData = toRaw(orderItemForm.value);
  orderItems.value.push({ ...rawFormData });
  orderItemsTable.value.push({
    id: rawFormData.productId,
    productName: productName.value,
    quantity: rawFormData.quantity,
    wholesaleCost: rawFormData.wholesaleCost,
    retailCost: rawFormData.retailCost,
    price: rawFormData.price,
    discount: rawFormData.discount,
    tax: rawFormData.tax,
    subTotal: orderItemTotal.value,
    expiryDate: rawFormData.expiryDate,
    markup: rawFormData.markup,
  });
  orderForm.value.subTotal += rawFormData.price - rawFormData.discount;
  orderForm.value.taxTotal += rawFormData.tax;
  orderForm.value.total =
    orderForm.value.subTotal + orderForm.value.charges + orderForm.value.taxTotal;
  resetFormData();
};

const removeEntry = (orderEntry: {
  id: number;
  productName: string;
  quantity: number;
  wholesaleCost: number;
  retailCost: number;
  price: number;
  discount: number;
  tax: number;
  subTotal: number;
  expiryDate: string;
}) => {
  orderItemsTable.value = orderItemsTable.value.filter(
    (orderItem) => orderItem.id !== orderEntry.id,
  );
  orderForm.value.taxTotal -= orderEntry.tax;
  orderForm.value.subTotal -= orderEntry.price - orderEntry.discount;
  orderForm.value.total =
    orderForm.value.subTotal + orderForm.value.charges + orderForm.value.taxTotal;
};

const addOrder = async () => {
  orderForm.value.orderItems = orderItems.value;
  if (orderId.value) {
    await orderStore.updateOrderApi(orderId.value, orderForm.value);
  } else {
    await orderStore.createOrderApi(orderForm.value);
  }
  basicNonStickyNotificationToggle();
  closeAddEditOrderForm();
};

const saveExpectedOrder = () => {
  isExpected.value = !isExpected.value;
  orderForm.value.charges = expectedCharges.value;
  orderForm.value.total =
    orderForm.value.subTotal + orderForm.value.charges + orderForm.value.taxTotal;
};

const setOrderForm = async () => {
  isExpected.value = true;
  orderForm.value.invoiceCode = order.value?.invoiceCode ?? '';
  orderForm.value.invoiceDate = order.value?.invoiceDate ?? dayjs().format(dateFormat);
  orderForm.value.status = order.value?.status ?? OrderStatus.IN_PROGRESS;
  orderForm.value.subTotal = order.value?.subTotal ?? 0;
  orderForm.value.taxTotal = order.value?.taxTotal ?? 0;
  orderForm.value.charges = order.value?.charges ?? 0;
  orderForm.value.total = order.value?.total ?? 0;
  orderForm.value.comment = order.value?.comment ?? '';
  selectedSupplier.value = order.value?.supplier ?? { id: 0, name: '' };

  orderItemsTable.value =
    order.value?.orderItems.map((orderItem) => {
      return {
        id: orderItem.id,
        productName: orderItem.product.name,
        quantity: orderItem.quantity,
        wholesaleCost: orderItem.wholesaleCost,
        retailCost: orderItem.retailCost,
        price: orderItem.price,
        discount: orderItem.discount,
        tax: orderItem.tax,
        subTotal: orderItem.price - orderItem.discount + orderItem.tax,
        expiryDate: orderItem.expiryDate,
        markup: orderItem.markup,
      };
    }) ?? [];
};

const getOrderData = async () => {
  if (orderId.value) {
    await orderStore.getOrderApi(orderId.value);
    await setOrderForm();
  }
};

const openCompleteModal = () => {
  confirmModal.value?.openConfirmModal();
};

const completeOrder = async () => {
  if (orderId.value) {
    await orderStore.completeOrderApi({ orderId: orderId.value });
    basicNonStickyNotificationToggle();
  }
};

onMounted(async () => {
  await tenantVerification();
  await getOrderData();
  await getProductsData();
  await getSuppliersData();
});
</script>
