<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Sales</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openAddEditSale()" variant="primary" class="mr-2 shadow-md">
        <span>Add Sale</span>
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
            v-for="(type, ikey) in saleStatusList"
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
            <Table.Th class="border-b-0 whitespace-nowrap">TYPE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">CREATED AT</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr v-for="(sale, ckey) in sales" :key="ckey" @click="openAddEditSale(sale.id)">
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ sale.code }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ sale.items }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ formatPrice(sale.netDue) }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              <span
                class="text-xs border rounded-full px-4 py-1 bg-opacity-20 border-opacity-5"
                :class="{
                  'bg-green-600 border-green-600 text-green-600':
                    sale.status === SaleStatus.COMPLETED,
                  'bg-red-600 border-red-600 text-red-600': sale.status === SaleStatus.IN_PROGRESS,
                  'bg-blue-800 border-blue-800 text-blue-800': sale.status === SaleStatus.PAID,
                }"
              >
                {{ getSaleStatus(sale.status) }}
              </span>
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ getSaleTypeName(sale.type) }}
            </Table.Td>
            <Table.Td
              class="box w-44 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
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
                <a
                  v-if="sale.status !== SaleStatus.PAID"
                  class="flex items-center text-danger"
                  href="#"
                  @click="openDeleteSaleModal(sale)"
                >
                  <Tippy content="Delete">
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </Tippy>
                </a>
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
  <DeleteModal ref="deleteModalRef" @submit="deleteSale" />
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
import _ from 'lodash';
import { computed, onMounted, provide, Ref, ref, watch } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import Table from '@/components/Base/Table';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useTenantStore from '@/stores/tenant';
import { useRoute, useRouter } from 'vue-router';
import PaginationComponent from '@/components/PaginationComponent.vue';
import SortOrder from '@/enums/sortOrder';
import { CREATED_AT } from '@/constants/properties';
import { formatPrice, getSaleStatus, getSaleTypeName } from '@/utils/helper';
import { saleTypeList } from '@/constants/saleTypeList';
import { saleStatusList } from '@/constants/saleStatusList';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import useSaleStore from '@/stores/sale';
import { SaleFragment } from '@/composables/models/SaleFragment';
import SaleStatus from '@/enums/saleStatus';
import { watchEffect } from 'vue';

const route = useRoute();
const router = useRouter();
const deleteModalRef = ref<InstanceType<typeof DeleteModal> | null>(null);

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
const saleStatus = ref<number | null>(null);
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
  await saleStore.getSalesApi({
    search: search.value,
    sortBy: CREATED_AT,
    sortOrder: SortOrder.DESC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    type: saleType.value,
    status: saleStatus.value,
    startDate: fromDate.value,
    endDate: toDate.value,
  });
};

const searchSale = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getSalesData();
  }
};

const openDeleteSaleModal = (sale: SaleFragment) => {
  deleteModalRef.value?.open(
    { id: sale.id, reference: sale.code },
    'Do you really want to delete the sale',
  );
};

const deleteSale = async (id: number) => {
  await saleStore.deleteSaleApi(id);
};

const openAddEditSale = (saleId: number | null = null) => {
  if (saleId) {
    router.push({
      name: 'edit-sale',
      params: { identifier: route.params.identifier, id: saleId },
    });
  } else {
    router.push({
      name: 'add-sale',
      params: { identifier: route.params.identifier },
    });
  }
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
