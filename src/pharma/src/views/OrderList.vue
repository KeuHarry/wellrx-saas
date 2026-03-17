<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Orders</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openAddEditOrder()" variant="primary" class="mr-2 shadow-md">
        <span>Add Order</span>
      </Button>
      <Menu>
        <Menu.Button :as="Button" class="px-2 !box">
          <span class="flex items-center justify-center w-5 h-5">
            <Lucide icon="Plus" class="w-4 h-4" />
          </span>
        </Menu.Button>
        <Menu.Items class="w-40">
          <Menu.Item> <Lucide icon="FileDown" class="w-4 h-4 mr-2" /> Import </Menu.Item>
          <Menu.Item> <Lucide icon="FileText" class="w-4 h-4 mr-2" /> Export to Excel </Menu.Item>
        </Menu.Items>
      </Menu>
      <div class="hidden mx-auto md:block text-slate-500"></div>
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
            @input="searchOrder"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by invoice or supplier..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">INVOICE #</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">INVOICE DATE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">INVOICE TOTAL</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">SUPPLIER</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">STATUS</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr v-for="(order, ckey) in orders" :key="ckey" @click="openAddEditOrder(order.id)">
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ order.invoiceCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ order.invoiceDate }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ order.total }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ order.supplier.name }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              <span
                class="text-xs border rounded-full px-4 py-1 bg-opacity-20 border-opacity-5"
                :class="{
                  'bg-green-600 border-green-600 text-green-600':
                    order.status === OrderStatus.COMPLETED,
                  'bg-blue-600 border-blue-600 text-blue-600':
                    order.status === OrderStatus.IN_PROGRESS,
                }"
              >
                {{ getOrderStatus(order.status) }}
              </span>
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
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger"
                    href="#"
                    @click="openDeleteOrderModal(order)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasOrder">
            <Table.Td
              :colspan="colspan"
              class="box w-full rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
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
      :totalItems="orderTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteOrder" />
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
import { computed, onMounted, provide, Ref, ref, watch, watchEffect } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import { Menu } from '@/components/Base/Headless';
import Table from '@/components/Base/Table';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useTenantStore from '@/stores/tenant';
import { useRoute, useRouter } from 'vue-router';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import SortOrder from '@/enums/sortOrder';
import { INVOICE_DATE } from '@/constants/properties';
import { getOrderStatus } from '@/utils/helper';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import useOrderStore from '@/stores/order';
import { Order } from '@/composables/models/Order';
import OrderStatus from '@/enums/orderStatus';

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

const search = ref<string>('');
const fromDate = ref<string | null>(null);
const toDate = ref<string | null>(null);

const currentPage = ref(1);
const paginate = ref(10);
const colspan = ref(0);

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
const orderStore = useOrderStore();
const toastStore = useToastStore();

// COMPUTED
const orders = computed(() => orderStore.getOrders);
const orderTotal = computed(() => orderStore.getOrderTotal);
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

const hasOrder = computed(() => orders.value.length > 0);

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
  await getOrdersData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getOrdersData = async () => {
  await orderStore.getOrdersApi({
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    search: search.value,
    sortBy: INVOICE_DATE,
    sortOrder: SortOrder.DESC,
    startDate: fromDate.value,
    endDate: toDate.value,
  });
};

const searchOrder = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getOrdersData();
  }
};

const openDeleteOrderModal = (order: Order) => {
  deleteModalRef.value?.open(
    { id: order.id, reference: `#${order.invoiceCode}` },
    'Do you really want to delete the order',
  );
};

const deleteOrder = async (id: number) => {
  await orderStore.deleteOrderApi(id);
};

const openAddEditOrder = (orderId: number | null = null) => {
  if (orderId) {
    router.push({
      name: 'edit-order',
      params: { identifier: route.params.identifier, id: orderId },
    });
  } else {
    router.push({
      name: 'add-order',
      params: { identifier: route.params.identifier },
    });
  }
};

watchEffect(async () => {
  await getOrdersData();
});

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getOrdersData();
});
</script>
