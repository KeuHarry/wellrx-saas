<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Suppliers</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openSupplierModal()" variant="primary" class="mr-2 shadow-md">
        Add Supplier
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
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchSupplier"
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
      <Table class="border-spacing-y-[5px] border-separate -mt-2">
        <Table.Thead>
          <Table.Tr>
            <Table.Th class="border-b-0 whitespace-nowrap">CODE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">NAME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PHONE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">CITY</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">ADDRESS </Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(supplier, ckey) in suppliers"
            :key="ckey"
            @click="openSupplierModal(supplier)"
          >
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ supplier.supplierCode }}
            </Table.Td>
            <Table.Td
              class="box w-80 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ supplier.name }}
            </Table.Td>
            <Table.Td
              class="box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ supplier.phone }}
            </Table.Td>
            <Table.Td
              class="box w-50 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ supplier.city }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ supplier.address }}
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
                <Tippy content="View invoices">
                  <a class="flex items-center text-blue-700" href="#">
                    <Lucide icon="Files" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger"
                    href="#"
                    @click="openDeleteSupplierModal(supplier)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasSupplier">
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
      :totalItems="supplierTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteSupplier" />
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
  <AddUpdateSupplierModal ref="supplierModal" @submit="createUpdateSupplier" />
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
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import { Ref } from 'vue';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';
import AddUpdateSupplierModal from '@/components/Modals/AddUpdateSupplierModal.vue';
import { Supplier } from '@/composables/models/Supplier';
import { SupplierRequest } from '@/composables/models/SupplierRequest';
import useSupplierStore from '@/stores/supplier';
import { NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';

const route = useRoute();
const supplierModal = ref<InstanceType<typeof AddUpdateSupplierModal> | null>(null);
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

// STORE
const tenantStore = useTenantStore();
const supplierStore = useSupplierStore();
const toastStore = useToastStore();

// COMPUTED
const suppliers = computed(() => supplierStore.getSuppliers);
const supplierTotal = computed(() => supplierStore.getSupplierTotal);
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

const startSupplierCount = computed(() => (suppliers.value.length > 0 ? 1 : 0));
const hasSupplier = computed(() => suppliers.value.length > 0);

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
  await getSuppliersData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getSuppliersData = async () => {
  await supplierStore.getSuppliersApi({
    search: search.value,
    sortBy: NAME,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchSupplier = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getSuppliersData();
  }
};

const openSupplierModal = (supplier: Supplier | null = null) => {
  supplierModal.value?.openSupplierModal(
    {
      name: supplier ? supplier.name : '',
      email: supplier ? supplier.email : '',
      phone: supplier ? supplier.phone : '',
      contact: supplier ? supplier.contact : '',
      city: supplier ? supplier.city : '',
      address: supplier ? supplier.address : '',
      postalCode: supplier ? supplier.postalCode : '',
      lastDate: supplier ? supplier.lastDate : '',
      eOrderNumber: supplier ? supplier.eOrderNumber : null,
      fax: supplier ? supplier.fax : '',
    },
    supplier ? supplier.id : null,
  );
};

const createUpdateSupplier = async (formData: Ref<SupplierRequest>, supplierId: number | null) => {
  if (supplierId) {
    await supplierStore.updateSupplierApi(supplierId, formData.value);
  } else {
    await supplierStore.createSupplierApi(formData.value);
  }
  basicNonStickyNotificationToggle();
  await getSuppliersData();
};

const openDeleteSupplierModal = (supplier: Supplier) => {
  deleteModalRef.value?.open(
    { id: supplier.id, reference: supplier.supplierCode },
    'Do you really want to delete the supplier',
  );
};

const deleteSupplier = async (id: number) => {
  await supplierStore.deleteSupplierApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getSuppliersData();
});
</script>
