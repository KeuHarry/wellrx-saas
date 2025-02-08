<template>
    <Dialog size="xl" :open="show">
      <Dialog.Panel>
        <Dialog.Title class="bg-blue-900">
          <h2 class="mr-auto text-base text-white font-medium">Process Sale - {{ sale?.code }} {{ sale?.createdAt }}</h2>
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
                  class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
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
                    <label for="discount" class="block text-sm/6 text-gray-900 font-medium">Discount:</label>
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
                <div class="flex items-center justify-between">
                    <label for="tendered" class="block text-sm/6 text-gray-900 font-medium">Tendered ({{ currency }}):</label>
                    <input
                    v-model="cashierForm.tendered"
                    type="number"
                    name="tendered"
                    id="tendered"
                    class="block w-[70%] h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 disabled:cursor-not-allowed disabled:bg-blue-50 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                    :disabled="hasCustomer"
                    />
                </div>
                <div class="flex items-center justify-between">
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
                    <Button @click="printReceipt" :disabled="!hasCustomer" variant="primary" type="button" class="mr-2">Receipt</Button>
                    <Button @click="printInvoice" variant="primary" type="button">Invoice</Button>
                </div>
                <div class="flex items-center">
                    <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
                        Cancel
                    </Button>
                    <Button :disabled="!canCompletePrint" variant="primary" type="button" @click="paySale" class="mr-1"> Complete + Print </Button>
                    <Button v-if="!cashierForm.isOneTimePayment" variant="primary" type="button" @click="openHistorySlideover" class="mr-1">View History</Button>
                    <Button v-if="!cashierForm.isOneTimePayment" variant="primary" type="button">Save</Button>
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
    }
  });
  const saleId = ref<number|null>(null);
  const isCompleted = ref<boolean>(false);

  // STORE
  const saleStore = useSaleStore();

  // COMPUTED
  const currency = computed(() => {
    const data = localStorage.getItem('settings');
    const settings = data ? JSON.parse(data) : [];
    return getValueByKey(settings, CURRENCY) ?? '$';
  });
  const sale = computed(() => saleId.value ? saleStore.getSale : null);
  const saleItems = computed(() => sale.value ? sale.value.saleItems : []);
  const saleTotal = computed(() => sale.value ? formatPrice(sale.value.total) : formatPrice(0));
  const saleTaxes = computed(() => sale.value ? formatPrice(sale.value.taxes) : formatPrice(0));
  const saleDiscount = computed(() => sale.value ? formatPrice(sale.value.discount) : formatPrice(0));
  const saleNetDue = computed(() => sale.value ? formatPrice(sale.value.netDue) : formatPrice(0));
  const changeAmount =  computed(() => {
    const amount = cashierForm.value.tendered > 0 ? cashierForm.value.tendered - (sale.value?.netDue ?? 0) : 0;
    return parseFloat(amount.toFixed(2));
  });

  const canCompletePrint = computed(() => isCompleted.value && cashierForm.value.tendered >= (sale.value?.netDue?? 0));
  const hasCustomer = computed(() => !!(sale.value?.id && sale.value?.customer?.id));
  const showReference = computed(() => cashierForm.value.paymentMethod !== PaymentMethod.CASH && cashierForm.value.paymentMethod !== PaymentMethod.CREDIT);
  
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
  };

  const setSaleDetails = async () => {
    cashierForm.value.isOneTimePayment = sale.value?.isOneTimePayment ?? true;
  };

  const getSaleData = async () => {
    if (saleId.value) {
        await saleStore.getSaleApi(saleId.value);
        await setSaleDetails();
    }
  };

  const printInvoice = () => {
    if (saleId.value) {
        saleStore.printSaleInvoiceApi(saleId.value);
    }
  };

  const printReceipt = () => {
    if (saleId.value) {
        saleStore.printSaleReceiptApi(saleId.value);
    }
  };

  const openHistorySlideover = () => {
    paymentHistoryslideoverRef.value?.openSlideover();
  };
  
  const paySale = async () => {
    if (saleId.value) {
      cashierForm.value.saleId = saleId.value;
    }
    await saleStore.paySaleApi(cashierForm.value);
    emit('salesUpdated');
  };
  
  defineExpose({
    openProcessViewSaleModal,
  });

  onMounted(async () => {
    await getSaleData();
  });
</script>
  