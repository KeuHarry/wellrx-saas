<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Cashier</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openCashRegisterModal" variant="primary" class="mr-2 shadow-md">
        <Lucide icon="Key" class="w-4 h-4 mr-2" />
        Close
      </Button>
      <div class="hidden mx-auto md:block text-slate-500"></div>
      <div class="mr-3 h-9">
        <select
          v-model="saleType"
          class="h-9 border border-gray-300 rounded-md shadow-sm text-sm"
          name="sale-type"
          id="sale-type"
        >
          <option
            v-for="(type, ikey) in saleTypeList"
            :key="ikey"
            :value="type.value"
            :class="[type.class ? type.class : 'text-slate-900']"
          >
            {{ type.name }}
          </option>
        </select>
      </div>
      <div class="mr-3 h-9">
        <select
          v-model="saleStatus"
          class="h-9 border border-gray-300 rounded-md shadow-sm text-sm"
          name="sale-status"
          id="sale-status"
        >
          <option
            v-for="(type, ikey) in cashierStatusList"
            :key="ikey"
            :value="type.value"
            :class="[type.class ? type.class : 'text-slate-900']"
          >
            {{ type.name }}
          </option>
        </select>
      </div>
      <div class="flex items-center mr-3 h-9">
        <label for="from-date" class="block mr-2 text-sm/6 text-gray-600">From</label>
        <Litepicker
          v-model="fromDate"
          :options="dateOptions"
          placeholder="YYYY-MM-DD"
          class="block w-28 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
        />
      </div>
      <div class="flex items-center mr-3 h-9">
        <label for="to-date" class="block mr-2 text-sm/6 text-gray-600">To</label>
        <Litepicker
          v-model="toDate"
          :options="dateOptions"
          placeholder="YYYY-MM-DD"
          class="block w-28 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
        />
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchSale"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by sale#..."
          />
          <Lucide icon="Search" class="absolute inset-y-0 right-0 w-4 h-4 my-auto mr-3" />
        </div>
      </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="col-span-12 overflow-auto intro-y lg:overflow-visible">
      <Table class="border-spacing-y-[4px] border-separate -mt-2">
        <Table.Thead>
          <Table.Tr>
            <Table.Th class="border-b-0 whitespace-nowrap">SALE #</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">ITEMS</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">NET DUE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">STATUS</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">CREATED AT</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(sale, ckey) in sales"
            :key="ckey"
            class="intro-x"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              {{ sale.code }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              {{ sale.items }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              {{ formatPrice(sale.netDue) }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              <span
                class="text-xs border rounded-full px-4 py-1 bg-opacity-20 border-opacity-5"
                :class="{
                  'bg-red-600 border-red-600 text-red-600':
                    sale.cashierStatus === CashierStatus.PENDING,
                  'bg-yellow-600 border-yellow-600 text-yellow-600': sale.cashierStatus === CashierStatus.IN_PROGRESS,
                  'bg-blue-700 border-blue-700 text-blue-700': sale.cashierStatus === CashierStatus.PAID,
                }"
              >
                {{ getCashierStatus(sale.cashierStatus) }}
              </span>
            </Table.Td>
            <Table.Td
              class="box w-44 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              {{ sale.createdAt }}
            </Table.Td>
            <Table.Td
              @click="
                (event) => {
                  event.stopPropagation();
                }
              "
              :class="[
                'box w-20 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600',
                'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',
              ]"
            >
              <div class="flex items-center justify-center">
                <Button
                    v-if="sale.status === SaleStatus.IN_PROGRESS"
                    class="text-xs w-20 hover:bg-sky-600/15 bg-sky-600 hover:border-sky-600/10 border-sky-600 text-sky-700 bg-opacity-20 border-opacity-5"
                  @click="openProcessViewSaleModal(sale)"
                >
                  VIEW
                </Button>
                <Button
                    v-if="sale.status === SaleStatus.COMPLETED"
                  class="text-xs w-20 hover:bg-teal-600/15 bg-teal-600 hover:border-teal-600/10 border-teal-600 text-teal-700 bg-opacity-20 border-opacity-5"
                  @click="openProcessViewSaleModal(sale, true)"
                >
                  PROCESS
                </Button>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasSale">
            <Table.Td
              :colspan="colspan"
              class="box w-full rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
            >
              <div class="grid grid-cols-1 p-5 text-center">
                <span class="text-slate-400">No Records</span>
              </div>
            </Table.Td>
          </Table.Tr>
        </Table.Tbody>
      </Table>
    </div>
    <!-- END: Data List -->
    <PaginationComponent
      :totalItems="saleTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
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
  <OpenCloseCashRegisterModal ref="cashRegisterModal" @submit="closeCashRegister" />
  <ProcessViewSaleModal ref="processViewSaleModal" @salesUpdated="getSalesData" />
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Table from '@/components/Base/Table';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useTenantStore from '@/stores/tenant';
import { useRoute, useRouter } from 'vue-router';
import PaginationComponent from '@/components/PaginationComponent.vue';
import SortOrder from '@/enums/sortOrder';
import { CREATED_AT } from '@/constants/properties';
import { saleTypeList } from '@/constants/saleTypeList';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import useSaleStore from '@/stores/sale';
import { SaleFragment } from '@/composables/models/SaleFragment';
import { watchEffect } from 'vue';
import OpenCloseCashRegisterModal from '@/components/Modals/OpenCloseCashRegisterModal.vue';
import useCashRegisterStore from '@/stores/cashRegister';
import CashRegisterStatus from '@/enums/cashRegisterStatus';
import { cashierStatusList } from '@/constants/cashierStatusList';
import CashierStatus from '@/enums/cashierStatus';
import { formatPrice, getCashierStatus } from '@/utils/helper';
import SaleStatus from '@/enums/saleStatus';
import ProcessViewSaleModal from '@/components/Modals/ProcessViewSaleModal.vue';

const route = useRoute();
const cashRegisterModal = ref<InstanceType<typeof OpenCloseCashRegisterModal> | null>(null);
const processViewSaleModal = ref<InstanceType<typeof ProcessViewSaleModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const search = ref('');
const currentPage = ref(1);
const paginate = ref(10);
const colspan = ref(0);
const saleType = ref<number | null>(null);
const saleStatus = ref<number | null>(CashierStatus.PENDING);
const fromDate = ref<string | null>(null);
const toDate = ref<string | null>(null);

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

// STORE
const tenantStore = useTenantStore();
const saleStore = useSaleStore();
const toastStore = useToastStore();
const cashRegisterStore = useCashRegisterStore();

// COMPUTED
const sales = computed(() => saleStore.getSales);
const saleTotal = computed(() => saleStore.getSaleTotal);
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

const hasSale = computed(() => sales.value.length > 0);

const cashRegister = computed(() => {
  const data = localStorage.getItem('cashRegister');
  const cashRegister: { id: number; status: number } | null = data ? JSON.parse(data) : null;

  return cashRegister;
});

//METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getColspan = () => {
  const thElements = document.querySelectorAll('thead th');
  colspan.value = thElements.length;
};

const handlePageChange = async (page: number) => {
  currentPage.value = page;
  await getSalesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getSalesData = async () => {
  await saleStore.getUnPaidSalesApi({
    search: search.value,
    sortBy: CREATED_AT,
    sortOrder: SortOrder.DESC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    type: saleType.value,
    cashierStatus: saleStatus.value,
    startDate: fromDate.value,
    endDate: toDate.value,
  });
};

const searchSale = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getSalesData();
  }
};

const openCashRegisterModal = () => {
  cashRegisterModal.value?.openCashRegisterModal(cashRegister.value ? cashRegister.value.id : null);
};

const openProcessViewSaleModal = async (sale: SaleFragment, isCompleted: boolean = false) => {
    await processViewSaleModal.value?.openProcessViewSaleModal(sale.id, isCompleted);
};

const closeCashRegister = async () => {
  if (cashRegister.value) {
    await cashRegisterStore.closeCashRegisterApi(cashRegister.value.id, {
      status: CashRegisterStatus.CLOSE,
    });
  }
  basicNonStickyNotificationToggle();
};

watchEffect(async () => {
  await getSalesData();
});

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getSalesData();
});
</script>
