<template>
  <Dialog size="xl" :open="show">
    <Dialog.Panel>
      <Dialog.Title class="bg-blue-900">
        <h2 class="mr-auto text-base text-white font-medium">
          Process Sale - {{ sale?.code }} {{ sale?.createdAt }}
        </h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-cols-12 gap-4 p-10">
        <div class="col-span-5 space-y-4 bg-white border rounded-md shadow-md p-6">
          <div>
            <label for="payment-type" class="block text-sm/6 text-gray-600">Type</label>
            <div class="flex items-center h-9 gap-6">
              <div v-for="type in paymentTypes" :key="type.id" class="space-x-2 text-center">
                <input
                  type="radio"
                  :value="type.value"
                  v-model="cashierForm.isOneTimePayment"
                  :id="'id-' + type.id"
                  class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  disabled
                />
                <label class="mr-4">{{ type.name }}</label>
              </div>
            </div>
          </div>
          <div>
            <label for="payment-method" class="block text-sm/6 text-gray-600">Payment Mode</label>
            <select
              v-model="cashierForm.paymentMethod"
              name="payment-method"
              id="payment-method"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900"
            >
              <option :value="type.value" v-for="(type, tkey) in paymentMethods" :key="tkey">
                {{ type.name }}
              </option>
            </select>
          </div>
          <div v-if="showReference">
            <label for="reference" class="block text-sm/6 text-gray-600">Reference/Code</label>
            <input
              v-model="cashierForm.paymentReference"
              type="text"
              placeholder="Enter reference/code"
              name="reference"
              id="reference"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div>
            <label for="client-name" class="block text-sm/6 text-gray-600">Client/Customer</label>
            <input
              v-model="cashierForm.customerName"
              type="text"
              placeholder="Enter client name"
              name="client-name"
              id="client-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="hasCustomer"
            />
          </div>
        </div>
        <div class="col-span-7 space-y-2 bg-white border rounded-md shadow-md p-6">
          <div class="flex items-center justify-between">
            <label for="total" class="block text-sm/6 text-gray-900 font-medium">Total: </label>
            <input
              v-model="saleTotal"
              type="text"
              name="total"
              id="total"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="flex items-center justify-between">
            <label for="taxes" class="block text-sm/6 text-gray-900 font-medium">Taxes:</label>
            <input
              v-model="saleTaxes"
              type="text"
              name="taxes"
              id="taxes"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="flex items-center justify-between">
            <label for="discount" class="block text-sm/6 text-gray-900 font-medium"
              >Discount:</label
            >
            <input
              v-model="saleDiscount"
              type="text"
              name="discount"
              id="discount"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="flex items-center justify-between">
            <label for="net-due" class="block text-sm/6 text-gray-900 font-medium">Net Due:</label>
            <input
              v-model="saleNetDue"
              type="text"
              name="net-due"
              id="net-due"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div v-if="!cashierForm.isOneTimePayment" class="flex items-center justify-between">
            <label for="paid-amount" class="block text-sm/6 text-gray-900 font-medium"
              >Paid Amount:</label
            >
            <input
              v-model="salePaidAmount"
              type="text"
              name="paid-amount"
              id="paid-amount"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="flex items-center justify-between">
            <label for="tendered" class="block text-sm/6 text-gray-900 font-medium"
              >Tendered ({{ currency }}):</label
            >
            <input
              @input="onChangeTendered"
              v-model="cashierForm.tendered"
              type="number"
              name="tendered"
              id="tendered"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div v-if="!cashierForm.isOneTimePayment" class="flex items-center justify-between">
            <label for="amount" class="block text-sm/6 text-gray-900 font-medium"
              >Amount ({{ currency }}):</label
            >
            <input
              @input="onChangeTendered"
              v-model="partialPaymentForm.amount"
              type="number"
              name="amount"
              id="amount"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div v-if="!cashierForm.isOneTimePayment" class="flex items-center justify-between">
            <label for="partial-change" class="block text-sm/6 text-gray-900 font-medium"
              >Changes:</label
            >
            <input
              v-model="partialPaymentForm.change"
              type="text"
              name="partial-change"
              id="partial-change"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div v-if="cashierForm.isOneTimePayment" class="flex items-center justify-between">
            <label for="change" class="block text-sm/6 text-gray-900 font-medium">Changes:</label>
            <input
              v-model="changeAmount"
              type="text"
              name="change"
              id="change"
              class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
        </div>
        <div class="col-span-12 p-5 bg-white border rounded-md shadow-sm">
          <Table>
            <Table.Thead variant="dark">
              <Table.Tr>
                <Table.Th class="whitespace-nowrap">QOH</Table.Th>
                <Table.Th class="whitespace-nowrap">Product Name</Table.Th>
                <Table.Th class="whitespace-nowrap">Qty</Table.Th>
                <Table.Th class="whitespace-nowrap">Price ({{ currency }})</Table.Th>
                <Table.Th class="whitespace-nowrap">Total ({{ currency }})</Table.Th>
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>
              <Table.Tr v-for="(saleItem, ikey) in saleItems" :key="ikey">
                <Table.Td>{{ saleItem.product.qoh }}</Table.Td>
                <Table.Td>{{ saleItem.product.name }}</Table.Td>
                <Table.Td>{{ saleItem.quantity }}</Table.Td>
                <Table.Td>{{ saleItem.price }}</Table.Td>
                <Table.Td>{{ saleItem.total }}</Table.Td>
              </Table.Tr>
              <Table.Tr v-if="saleItems.length === 0">
                <Table.Td :colspan="10">
                  <div class="grid grid-cols-1 p-2 text-center">
                    <span class="text-slate-400">No Entry</span>
                  </div>
                </Table.Td>
              </Table.Tr>
            </Table.Tbody>
          </Table>
        </div>
      </div>
      <Dialog.Footer>
        <div class="flex col-span-12 justify-between">
          <div class="flex items-center">
            <Button
              @click="printReceipt"
              :disabled="!hasCustomer"
              variant="primary"
              type="button"
              class="mr-2"
              >Receipt</Button
            >
            <Button @click="printInvoice" variant="primary" type="button">Invoice</Button>
          </div>
          <div class="flex items-center">
            <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
              Cancel
            </Button>
            <Button
              :disabled="!canCompletePrint"
              variant="primary"
              type="button"
              @click="paySale"
              class="mr-1"
            >
              Complete + Print
            </Button>
            <Button
              v-if="!cashierForm.isOneTimePayment && canViewHistory"
              variant="primary"
              type="button"
              @click="openHistorySlideover"
              class="mr-1"
              >View History</Button
            >
            <Button
              v-if="!cashierForm.isOneTimePayment"
              @click="savePayment"
              variant="primary"
              type="button"
            >
              Save
            </Button>
          </div>
        </div>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
  <PaymentHistorySlideover ref="paymentHistoryslideoverRef" />
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, onMounted, ref } from 'vue';
import { tags } from '@/constants/tags';
import { feeTypes } from '@/constants/feeTypes';
import useSaleStore from '@/stores/sale';
import { paymentMethods } from '@/constants/paymentMethods';
import { formatPrice, getValueByKey } from '@/utils/helper';
import Table from '@/components/Base/Table';
import { SaleItem } from '@/composables/models/SaleItem';
import { CURRENCY } from '@/constants/settings';
import { paymentTypes } from '@/constants/paymentTypes';
import PaymentHistorySlideover from '../PaymentHistorySlideover.vue';
import { CashierOneTimePaymentRequest } from '@/composables/models/CashierRequest';
import PaymentMethod from '@/enums/paymentMethod';
import usePrintingStore from '@/stores/printing';
import { EntityType } from '@/enums/entityType';
import { PartialPaymentRequest } from '@/composables/models/PartialPaymentRequest';

const emit = defineEmits(['submit', 'salesUpdated']);

const paymentHistoryslideoverRef = ref<InstanceType<typeof PaymentHistorySlideover> | null>(null);

const show = ref(false);
const cashierForm = ref<CashierOneTimePaymentRequest>({
  saleId: 0,
  customerName: 'Client',
  paymentMethod: 0,
  paymentReference: '',
  isOneTimePayment: true,
  tendered: 0,
  reset() {
    this.customerName = '';
    this.paymentMethod = 0;
    this.paymentReference = '';
    this.isOneTimePayment = true;
    this.tendered = 0;
  },
});
const partialPaymentForm = ref<PartialPaymentRequest>({
  saleId: 0,
  paymentMethod: 0,
  reference: '',
  tendered: 0,
  amount: 0,
  change: 0,
});
const saleId = ref<number | null>(null);
const isCompleted = ref<boolean>(false);

// STORE
const saleStore = useSaleStore();
const printingStore = usePrintingStore();

// COMPUTED
const currency = computed(() => {
  const data = localStorage.getItem('settings');
  const settings = data ? JSON.parse(data) : [];
  return getValueByKey(settings, CURRENCY) ?? '$';
});
const sale = computed(() => (saleId.value ? saleStore.getSale : null));
const saleItems = computed(() => (sale.value ? sale.value.saleItems : []));
const saleTotal = computed(() => (sale.value ? formatPrice(sale.value.total) : formatPrice(0)));
const saleTaxes = computed(() => (sale.value ? formatPrice(sale.value.taxes) : formatPrice(0)));
const saleDiscount = computed(() =>
  sale.value ? formatPrice(sale.value.discount) : formatPrice(0),
);
const saleNetDue = computed(() => (sale.value ? formatPrice(sale.value.netDue) : formatPrice(0)));
const changeAmount = computed(() => {
  const amount =
    cashierForm.value.tendered > 0 ? cashierForm.value.tendered - (sale.value?.netDue ?? 0) : 0;
  return parseFloat(amount.toFixed(2));
});

const canCompletePrint = computed(
  () => isCompleted.value && cashierForm.value.tendered >= (sale.value?.netDue ?? 0),
);
const hasCustomer = computed(() => !!(sale.value?.id && sale.value?.customer?.id));
const showReference = computed(() => cashierForm.value.paymentMethod !== PaymentMethod.CASH);
const salePaidAmount = computed(() =>
  sale.value ? formatPrice(sale.value.paidAmount) : formatPrice(0),
);
const salePayments = computed(() => (sale.value ? sale.value.payments : []));
const canViewHistory = computed(() => salePayments.value.length > 0);

// METHODS
const openProcessViewSaleModal = async (id: number, isComplete: boolean) => {
  show.value = true;
  saleId.value = id;
  isCompleted.value = isComplete;
  await getSaleData();
};

const closeModal = () => {
  show.value = false;
  cashierForm.value.reset();
  resetPartialPaymentForm();
};

const resetPartialPaymentForm = () => {
  partialPaymentForm.value.saleId = 0;
  partialPaymentForm.value.paymentMethod = 0;
  partialPaymentForm.value.reference = '';
  partialPaymentForm.value.tendered = 0;
  partialPaymentForm.value.amount = 0;
  partialPaymentForm.value.change = 0;
  cashierForm.value.paymentMethod = 0;
  cashierForm.value.paymentReference = '';
  cashierForm.value.tendered = 0;
};

const setSaleDetails = async () => {
  cashierForm.value.isOneTimePayment = sale.value?.isOneTimePayment ?? true;
  cashierForm.value.customerName = sale.value?.customer ? sale.value?.customer.name : 'Client';
};

const getSaleData = async () => {
  if (saleId.value) {
    await saleStore.getSaleApi(saleId.value);
    await setSaleDetails();
  }
};

const printInvoice = () => {
  if (saleId.value) {
    printingStore.printInvoiceApi(saleId.value, EntityType.SALE);
  }
};

const printReceipt = () => {
  if (saleId.value) {
    saleStore.printSaleReceiptApi(saleId.value);
  }
};

const openHistorySlideover = () => {
  if (sale.value) {
    paymentHistoryslideoverRef.value?.openSlideover(sale.value.code, salePayments.value);
  }
};

const paySale = async () => {
  if (saleId.value) {
    cashierForm.value.saleId = saleId.value;
  }
  await saleStore.paySaleApi(cashierForm.value);
  emit('salesUpdated');
};

const savePayment = async () => {
  if (saleId.value) {
    await saleStore.performPartialPaymentApi({
      saleId: saleId.value,
      paymentMethod: cashierForm.value.paymentMethod,
      reference: cashierForm.value.paymentReference,
      tendered: cashierForm.value.tendered,
      amount: partialPaymentForm.value.amount,
      change: partialPaymentForm.value.change,
    });
    resetPartialPaymentForm();
    await getSaleData();
    emit('salesUpdated');
  }
};

const onChangeTendered = () => {
  const change = cashierForm.value.tendered - partialPaymentForm.value.amount;
  partialPaymentForm.value.change = parseFloat(change.toFixed(2));
};

defineExpose({
  openProcessViewSaleModal,
});

onMounted(async () => {
  await getSaleData();
});
</script>
