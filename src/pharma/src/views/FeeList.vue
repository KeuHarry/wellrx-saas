<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Fees / Taxes</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openFeeModal()" variant="primary" class="mr-2 shadow-md"> Add Fee/Tax</Button>
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
      <div class="hidden mx-auto md:block text-slate-500">
        Showing {{ startFeeCount }} to {{ paginate }} of {{ feeTotal }} entries
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchFee"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by code or name..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">CODE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">NAME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">COST</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">TAG</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">DESCRIPTION</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(fee, ckey) in fees"
            :key="ckey"
            class="intro-x"
            @click="openFeeModal(fee)"
          >
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ fee.feeCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ fee.name }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ fee.cost }}
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              <span class="border p-1 shadow-sm font-medium text-xs bg-purple-200">{{
                tagName(fee.tag)
              }}</span>
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ fee.description }}
            </Table.Td>
            <Table.Td
              @click="
                (event) => {
                  event.stopPropagation();
                }
              "
              :class="[
                'box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600',
                'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',
              ]"
            >
              <div class="flex items-center justify-center">
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger"
                    href="#"
                    @click="openDeleteCategoryModal(fee)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasFee">
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
      :totalItems="feeTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteFee" />
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
  <AddUpdateFeeModal ref="feeModal" @submit="createUpdateFee" />
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import { Menu } from '@/components/Base/Headless';
import Table from '@/components/Base/Table';
import AddUpdateFeeModal from '@/components/Modals/AddUpdateFeeModal.vue';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import { Ref } from 'vue';
import useFeeStore from '@/stores/fee';
import { Fee } from '@/composables/models/Fee';
import FeeTag from '@/enums/feeTag';
import { TAG_NAME } from '@/constants/tags';
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import SortOrder from '@/enums/sortOrder';
import { FEE_CODE } from '@/constants/properties';
import PaginationComponent from '@/components/PaginationComponent.vue';

const feeModal = ref<InstanceType<typeof AddUpdateFeeModal> | null>(null);
const deleteModalRef = ref<InstanceType<typeof DeleteModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const route = useRoute();
const search = ref('');
const currentPage = ref(1);
const paginate = ref(10);
const colspan = ref(0);

// STORE
const tenantStore = useTenantStore();
const feeStore = useFeeStore();
const toastStore = useToastStore();

// COMPUTED
const fees = computed(() => feeStore.getFees);
const feeTotal = computed(() => feeStore.getFeeTotal);
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

const startFeeCount = computed(() => (fees.value.length > 0 ? 1 : 0));
const hasFee = computed(() => fees.value.length > 0);

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
  await getFeesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getFeesData = async () => {
  await feeStore.getFeesApi({
    search: search.value,
    sortBy: FEE_CODE,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchFee = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getFeesData();
  }
};

const tagName = (tag: number) => {
  if (tag === FeeTag.FEE) {
    return TAG_NAME.FEE;
  }

  return TAG_NAME.TAX;
};

const openFeeModal = (fee: Fee | null = null) => {
  feeModal.value?.openFeeModal(fee ? fee : { name: '', cost: 0, tag: 0, description: '' });
};

const createUpdateFee = async (
  formData: Ref<{
    id: number | null;
    name: string;
    cost: number;
    tag: number;
    description: string;
  }>,
) => {
  if (formData.value.id) {
    await feeStore.updateFeeApi(formData.value.id, {
      name: formData.value.name,
      cost: formData.value.cost,
      tag: formData.value.tag,
      description: formData.value.description,
    });
  } else {
    await feeStore.createFeeApi({
      name: formData.value.name,
      cost: formData.value.cost,
      tag: formData.value.tag,
      description: formData.value.description,
    });
  }
  basicNonStickyNotificationToggle();
  await getFeesData();
};

const openDeleteCategoryModal = (fee: Fee) => {
  deleteModalRef.value?.open({ id: fee.id, reference: fee.feeCode });
};

const deleteFee = async (id: number) => {
  await feeStore.deleteFeeApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getFeesData();
});
</script>
