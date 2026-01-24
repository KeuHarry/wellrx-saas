<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Cash Register</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openCashRegisterModal" variant="primary" class="mr-2 shadow-md">
        <Lucide :icon="cashRegisterIcon" class="w-4 h-4 mr-2" />
        {{ cashRegister ? 'Close' : 'Open' }}
      </Button>
      <div class="hidden mx-auto md:block text-slate-500"></div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchCashRegister"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by opener..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">OPENED BY</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">OPENING TIME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">AMOUNT</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">CLOSED BY</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">STATUS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr v-for="(cashRegister, ckey) in cashRegisters" :key="ckey">
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ cashRegister.openedBy }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ cashRegister.createdAt }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ formatPrice(cashRegister.amount) }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ cashRegister.closedBy }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ cashRegisterStatusName(cashRegister.status) }}
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasCashRegister">
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
      :totalItems="cashRegisterTotal"
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
  <OpenCloseCashRegisterModal ref="cashRegisterModal" @submit="openCloseCashRegister" />
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
import { useRoute } from 'vue-router';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import SortOrder from '@/enums/sortOrder';
import { CREATED_AT } from '@/constants/properties';
import { formatPrice } from '@/utils/helper';
import OpenCloseCashRegisterModal from '@/components/Modals/OpenCloseCashRegisterModal.vue';
import useCashRegisterStore from '@/stores/cashRegister';
import { defaultItemsPerPage } from '@/constants/pagination';
import CashRegisterStatus from '@/enums/cashRegisterStatus';

const route = useRoute();
const cashRegisterModal = ref<InstanceType<typeof OpenCloseCashRegisterModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const search = ref('');
const currentPage = ref(1);
const paginate = ref(defaultItemsPerPage);
const colspan = ref(0);

// STORE
const tenantStore = useTenantStore();
const cashRegisterStore = useCashRegisterStore();
const toastStore = useToastStore();

// COMPUTED
const cashRegisters = computed(() => cashRegisterStore.getCashRegisters);
const cashRegisterTotal = computed(() => cashRegisterStore.getCashRegisterTotal);
const cashRegister = computed(() => {
  const data = localStorage.getItem('cashRegister');
  const cashRegister: { id: number; status: number } | null = data ? JSON.parse(data) : null;

  return cashRegister;
});
const cashRegisterIcon = computed(() => (cashRegister.value ? 'Key' : 'FolderOpen'));
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

const startCashRegisterCount = computed(() => (cashRegisters.value.length > 0 ? 1 : 0));
const hasCashRegister = computed(() => cashRegisters.value.length > 0);

// METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getColspan = () => {
  const thElements = document.querySelectorAll('thead th');
  colspan.value = thElements.length;
};

const handlePageChange = async (page: number) => {
  currentPage.value = page;
  await getCashRegistersData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getCashRegistersData = async () => {
  await cashRegisterStore.getCashRegistersApi({
    search: search.value,
    sortBy: CREATED_AT,
    sortOrder: SortOrder.DESC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const cashRegisterStatusName = (status: number) => {
  if (status === CashRegisterStatus.CLOSE) {
    return 'Closed';
  }

  return 'Opened';
};

const searchCashRegister = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getCashRegistersData();
  }
};

const openCashRegisterModal = () => {
  cashRegisterModal.value?.openCashRegisterModal(cashRegister.value ? cashRegister.value.id : null);
};

const openCloseCashRegister = async () => {
  if (cashRegister.value) {
    await cashRegisterStore.closeCashRegisterApi(cashRegister.value.id, {
      status: CashRegisterStatus.CLOSE,
    });
  } else {
    await cashRegisterStore.openCashRegisterApi({
      status: CashRegisterStatus.OPEN,
    });
  }
  basicNonStickyNotificationToggle();
  await getCashRegistersData();
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getCashRegistersData();
});
</script>
