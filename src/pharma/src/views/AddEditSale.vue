<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="flex col-span-12 h-14 px-5 justify-between">
      <div class="flex items-center">
        <h2 class="text-lg font-medium">Sale Details {{ saleCode }}</h2>
        <span
          v-if="sale"
          class="border rounded-full text-xs px-4 py-1 ml-1 bg-opacity-20 border-opacity-5"
          :class="{
            'bg-red-600 border-red-600 text-red-600': isInProgress,
            'bg-green-600 border-green-600 text-green-600': isCompleted,
            'bg-blue-800 border-blue-800 text-blue-800': isPaid,
          }"
          >{{ saleStatus }}
        </span>
      </div>
      <div class="flex items-center">
        <Menu v-if="sale">
          <Menu.Button :as="Button" class="px-2 !box">
            <span class="flex items-center justify-center w-5 h-5">
              <Lucide icon="Printer" class="w-4 h-4" />
            </span>
          </Menu.Button>
          <Menu.Items class="w-40">
            <Menu.Item @click="printProforma"
              ><Lucide icon="FileDown" class="w-4 h-4 mr-2" /> Proforma Invoice</Menu.Item
            >
            <Menu.Item v-if="isPaid" @click="printInvoice"
              ><Lucide icon="FileDown" class="w-4 h-4 mr-2" />Invoice</Menu.Item
            >
          </Menu.Items>
        </Menu>
        <Button
          v-if="!isCompleted && !isPaid"
          variant="primary"
          @click="addSale"
          :disabled="!canSaveSale"
          class="ml-3"
          >Save</Button
        >
        <Button
          v-if="!isCompleteOrPaid"
          @click="openCompleteModal"
          :disabled="!canComplete"
          class="hover:bg-teal-600/15 bg-teal-600 hover:border-teal-600/10 border-teal-600 text-teal-600 bg-opacity-20 border-opacity-5 ml-3"
          >Complete</Button
        >
        <Button class="ml-3" variant="outline-secondary" @click="closeAddEditSaleForm"
          >Cancel</Button
        >
      </div>
    </div>
    <!-- <div class="mb-6">
      <label class="flex items-center space-x-2">
        <span class="text-gray-700">Pricing Mode:</span>
        <select
          v-model="pricingMode"
          class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="retail">Retail</option>
          <option value="wholesale">Wholesale</option>
        </select>
      </label>
    </div> -->
    <!-- Search and Product List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5 px-5 pb-5">
      <!-- Product Search and List -->
      <div v-if="!isCompleted && !isPaid" class="bg-white md:h-[420px] p-6 rounded-lg shadow-md">
        <div class="flex">
          <h3 class="font-medium">Pricing Mode:</h3>
          <FormSwitch class="w-full sm:w-auto sm:ml-auto sm:mt-0 mb-5">
            <FormSwitch.Label htmlFor="retail-cost"> Wholesale cost </FormSwitch.Label>
            <FormSwitch.Input
              v-model="isRetail"
              id="show-example-1"
              @click="togglePricingMode"
              class="ml-3 mr-0"
              type="checkbox"
            />
            <FormSwitch.Label htmlFor="wholesale-cost"> Retail cost </FormSwitch.Label>
          </FormSwitch>
        </div>

        <input
          v-model="searchQuery"
          @input="searchProduct"
          type="text"
          placeholder="Search for products..."
          class="w-full p-2 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 mb-4 focus:outline-none focus:ring-indigo-500"
        />
        <ul>
          <li
            v-for="product in products"
            :key="product.id"
            @click="addToCart(product)"
            class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-md cursor-pointer"
          >
            <div>
              <p class="font-semibold">{{ product.name }} ({{ product.qoh }})</p>
              <p class="text-sm text-gray-600">
                {{
                  isRetail ? formatPrice(product.retailCost) : formatPrice(product.wholesaleCost)
                }}
              </p>
            </div>
            <!-- <button
                @click="addToCart(product)"
                class="px-4 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors"
              >
                Add to Cart
              </button> -->
          </li>
        </ul>
      </div>

      <!-- Cart and Checkout -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Cart</h2>
        <ul class="mb-4">
          <li
            v-for="(item, index) in cart"
            :key="index"
            class="flex justify-between items-center p-2 border-b border-gray-200"
          >
            <div>
              <p class="font-semibold">
                {{ item.productName }}
                <span class="font-normal"
                  >{{ item.shelf ? ` shelf: ${item.shelf}` : '' }}
                  {{ item.fee ? `| fee: ${item.fee}` : '' }}</span
                >
              </p>
              <p class="text-sm text-gray-600">
                <input
                  v-model.number="item.price"
                  type="number"
                  class="w-24 text-sm p-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500"
                  :disabled="isCompleted || isPaid"
                />
                <span class="mr-2">{{ item.tax ? `+ tax: ${item.tax}` : '' }}</span>
                x {{ item.quantity }}
              </p>
            </div>
            <div class="flex items-center gap-2">
              <button
                @click="updateQuantity(index, item.quantity - 1)"
                class="px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 disabled:cursor-not-allowed transition-colors"
                :disabled="item.quantity <= 1 || isCompleted || isPaid"
              >
                -
              </button>
              <span>{{ item.quantity }}</span>
              <button
                @click="updateQuantity(index, item.quantity + 1)"
                class="px-2 py-1 bg-green-100 text-green-600 rounded-md hover:bg-green-200 disabled:cursor-not-allowed transition-colors"
                :disabled="isCompleted || isPaid"
              >
                +
              </button>
              <button
                @click="removeFromCart(index)"
                class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 disabled:cursor-not-allowed transition-colors"
                :disabled="isCompleted || isPaid"
              >
                Remove
              </button>
            </div>
          </li>
        </ul>

        <!-- Tax and Discount Inputs -->
        <div class="mt-6 space-y-4">
          <div>
            <label for="tax" class="block text-sm font-medium text-gray-700"
              >Tax+Fee ({{ currency }})</label
            >
            <input
              v-model.number="saleForm.taxes"
              type="number"
              id="tax"
              min="0"
              class="w-full border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-blue-500"
              :disabled="isCompleted || isPaid"
            />
          </div>
          <div>
            <label for="discount" class="block text-sm font-medium text-gray-700"
              >Discount ({{ currency }})</label
            >
            <input
              v-model.number="saleForm.discount"
              type="number"
              id="discount"
              min="0"
              class="w-full border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-blue-500"
              :disabled="isCompleted || isPaid"
            />
          </div>
        </div>

        <!-- Total and Checkout -->
        <div class="mt-6">
          <p class="text-lg font-bold mb-2">Subtotal: {{ currency }}{{ subtotal }}</p>
          <p class="text-[16px] text-gray-700 mb-2">Tax: {{ currency }}{{ saleForm.taxes }}</p>
          <p class="text-[16px] text-gray-700 mb-2">
            Discount: -{{ currency }}{{ saleForm.discount }}
          </p>
          <p class="text-2xl font-bold mb-4">Total: {{ currency }}{{ netDue }}</p>
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
  <ConfirmModal
    ref="confirmModal"
    :modal-title="modalTitle"
    :content="content"
    @submit="completeSale"
  />
</template>

<script setup lang="ts">
import Lucide from '@/components/Base/Lucide';
import Button from '@/components/Base/Button';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import SortOrder from '@/enums/sortOrder';
import ConfirmModal from '@/components/Modals/ConfirmModal.vue';
import { useRoute, useRouter } from 'vue-router';
import { computed, onMounted, provide, ref, toRaw } from 'vue';
import useTenantStore from '@/stores/tenant';
import useSaleStore from '@/stores/sale';
import { defaultOffset } from '@/constants/pagination';
import { LABEL } from '@/constants/properties';
import useProductStore from '@/stores/product';
import { formatPrice, getSaleStatus, getValueByKey } from '@/utils/helper';
import SaleStatus from '@/enums/saleStatus';
import { InStockProduct } from '@/composables/models/InStockProduct';
import { SaleItemRequest } from '@/composables/models/SaleItemRequest';
import { FormSwitch } from '@/components/Base/Form';
import { CURRENCY } from '@/constants/settings';
import FeeType from '@/enums/feeType';
import { SaleRequest } from '@/composables/models/SaleRequest';
import { Menu } from '@/components/Base/Headless';

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

const searchQuery = ref<string>('');
const modalTitle = ref('Complete Sale');
const content = ref('Do you really want to complete the sale ?');

const saleForm = ref<SaleRequest>({
  saleId: null,
  status: SaleStatus.IN_PROGRESS,
  total: 0,
  taxes: 0,
  discount: 0,
  netDue: 0,
  saleItems: [],
});
const cart = ref<SaleItemRequest[]>([]);
const isRetail = ref<boolean>(true);

// STORE
const tenantStore = useTenantStore();
const toastStore = useToastStore();
const productStore = useProductStore();
const saleStore = useSaleStore();

// COMPUTED
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

const saleId = computed(() => (route.params.id ? parseInt(route.params.id.toString()) : null));
const sale = computed(() => (saleId.value ? saleStore.getSale : null));
const products = computed(() => productStore.getInStockProducts);
const saleStatus = computed(() => (sale.value ? getSaleStatus(sale.value?.status) : ''));
const currency = computed(() => {
  const data = localStorage.getItem('settings');
  const settings = data ? JSON.parse(data) : [];
  return getValueByKey(settings, CURRENCY) ?? '$';
});

const saleCode = computed(() => `${sale.value ? `- ${sale.value.code}` : ''}`);
const isCompleted = computed(() => sale.value?.status === SaleStatus.COMPLETED);
const isInProgress = computed(() => sale.value?.status === SaleStatus.IN_PROGRESS);
const isPaid = computed(() => sale.value?.status === SaleStatus.PAID);
const isCompleteOrPaid = computed(() => isCompleted.value || isPaid.value);
const canSaveSale = computed(() => cart.value.length > 0);

const subtotal = computed(() => {
  const amount = cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
  return parseFloat(amount.toFixed(2));
});

const netDue = computed(() => {
  const amount = subtotal.value + saleForm.value.taxes - saleForm.value.discount;
  return parseFloat(amount.toFixed(2));
});
const canComplete = computed(
  () => sale.value?.status !== SaleStatus.COMPLETED && cart.value.length > 0,
);

// METHOD
const tenantVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const closeAddEditSaleForm = () => {
  router.push({
    name: 'sale-list',
    params: { identifier: route.params.identifier },
  });
};

const togglePricingMode = () => {
  isRetail.value = !isRetail.value;
};

const setSaleForm = async () => {
  if (sale.value) {
    saleForm.value.taxes = sale.value.taxes;
    saleForm.value.discount = sale.value.discount;
    cart.value = sale.value.saleItems.map((saleItem) => {
      return {
        productId: saleItem.product.id,
        productName: saleItem.product.name,
        quantity: saleItem.quantity,
        price: saleItem.price,
        tax: saleItem.tax,
        fee: saleItem.fee,
        total: saleItem.total,
        shelf: saleItem.product.shelf,
      };
    });
  }
};

const addToCart = (product: InStockProduct) => {
  const existingItem = cart.value.find((item) => item.productId === product.id);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    const rawProduct = toRaw(product);
    let tax = null;
    if (rawProduct.tax) {
      tax =
        rawProduct.tax.type === FeeType.COST
          ? rawProduct.tax.value
          : isRetail.value
            ? (rawProduct.retailCost * rawProduct.tax.value) / 100
            : (rawProduct.wholesaleCost * rawProduct.tax.value) / 100;
    }
    cart.value.push({
      productId: rawProduct.id,
      productName: rawProduct.name,
      quantity: 1,
      price: isRetail.value ? rawProduct.retailCost : rawProduct.wholesaleCost,
      tax: parseFloat(tax?.toFixed(2) ?? '0'),
      fee: null,
      total: (isRetail.value ? rawProduct.retailCost : rawProduct.wholesaleCost) + (tax ?? 0),
    });
  }
  saleForm.value.taxes = cart.value.reduce((sum, item) => sum + (item.tax ?? 0), 0);
};

const removeFromCart = (index: number) => {
  cart.value.splice(index, 1);
  saleForm.value.taxes = cart.value.reduce((sum, item) => sum + (item.tax ?? 0), 0);
};

const updateQuantity = (index: number, quantity: number) => {
  if (quantity > 0) {
    cart.value[index].quantity = quantity;
    cart.value[index].total = quantity * (cart.value[index].price + (cart.value[index].tax ?? 0));
  }
};

const getInStockProductsData = async (searchString: string = '') => {
  await productStore.getInStockProductsApi({
    search: searchString,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: 5,
  });
};

const searchProduct = async () => {
  if (searchQuery.value.length === 0 || searchQuery.value.length >= 3) {
    await getInStockProductsData(searchQuery.value);
  }
};

const getSaleData = async () => {
  if (saleId.value) {
    await saleStore.getSaleApi(saleId.value);
    await setSaleForm();
  }
};

const resetFormData = () => {
  cart.value = [];
  saleForm.value.total = 0;
  saleForm.value.taxes = 0;
  saleForm.value.discount = 0;
  saleForm.value.netDue = 0;
  saleForm.value.saleItems = [];
};

const addSale = async () => {
  saleForm.value.saleId = sale.value?.id ?? null;
  saleForm.value.total = subtotal.value;
  saleForm.value.netDue = netDue.value;
  saleForm.value.saleItems = cart.value;
  await saleStore.createSaleApi(saleForm.value);
  basicNonStickyNotificationToggle();
  closeAddEditSaleForm();
  resetFormData();
};

const openCompleteModal = () => {
  confirmModal.value?.openConfirmModal();
};

const completeSale = async () => {
  saleForm.value.status = SaleStatus.COMPLETED;
  await addSale();
};

const printInvoice = () => {
  if (saleId.value) {
    saleStore.printSaleInvoiceApi(saleId.value);
  }
};

const printProforma = () => {
  if (saleId.value) {
    saleStore.printSaleProformaApi(saleId.value);
  }
};

onMounted(async () => {
  await tenantVerification();
  await getSaleData();
  await getInStockProductsData();
});
</script>
