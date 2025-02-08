<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Inventory</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openAddInventory('in')" variant="primary" class="mr-2 shadow-md">
        <span>Add Inventory</span>
      </Button>
      <div class="hidden mx-auto md:block text-slate-500"></div>
      <div class="mr-3 h-9">
        <select
          v-model="inventoryType"
          class="h-9 border border-gray-300 rounded-md shadow-sm text-sm"
          name="inventory-type"
          id="inventory-type"
        >
          <option
            v-for="(type, ikey) in inventoryTypeList"
            :key="ikey"
            :value="type.value"
            :class="[type.class ? type.class : 'text-slate-900']"
          >
            {{ type.name }}
          </option>
        </select>
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchInventory"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by product name or barcode..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">PRODUCT</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">QTY</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">EXPIRY DATE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">SHELF</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">BATCH</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">TYPE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">DATE</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(inventory, ckey) in inventories"
            :key="ckey"
            class="intro-x"
            @click="openViewInventoryModal(inventory)"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.product?.label }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.quantity }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.expiryDate }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.product?.shelf }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.batchId }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ getInventoryTypeNameByValue(inventory.type) }}
            </Table.Td>
            <Table.Td
              class="box w-44 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ inventory.createdAt }}
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
                <Tippy content="inventory out">
                  <a
                    v-if="inventory.type === InventoryType.IN"
                    class="flex items-center text-indigo-700"
                    href="#"
                    @click="openInventoryOutModal(inventory)"
                  >
                    <Lucide icon="MinusCircle" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger"
                    href="#"
                    @click="openDeleteInventoryModal(inventory)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasInventory">
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
      :totalItems="inventoryTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteInventory" />
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
  <ViewInventoryModal ref="viewInventoryModal" />
  <InventoryOutModal ref="inventoryOutModal" @submit="saveInventoryOutEntry" />
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
import { inventoryTypeList } from '@/constants/inventoryTypeList';
import useInventoryStore from '@/stores/inventory';
import { Inventory } from '@/composables/models/Inventory';
import ViewInventoryModal from '@/components/Modals/ViewInventoryModal.vue';
import { getInventoryTypeNameByValue } from '@/utils/helper';
import InventoryType from '@/enums/inventoryType';
import InventoryOutModal from '@/components/Modals/InventoryOutModal.vue';
import { InventoryOutRequest } from '@/composables/models/InventoryOutRequest';

const route = useRoute();
const router = useRouter();
const viewInventoryModal = ref<InstanceType<typeof ViewInventoryModal> | null>(null);
const inventoryOutModal = ref<InstanceType<typeof InventoryOutModal> | null>(null);
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
const inventoryType = ref<number | null>(null);

// STORE
const tenantStore = useTenantStore();
const inventoryStore = useInventoryStore();
const toastStore = useToastStore();

// COMPUTED
const inventories = computed(() => inventoryStore.getInventoryList);
const inventoryTotal = computed(() => inventoryStore.getInventoryTotal);
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

const startInventoryCount = computed(() => (inventories.value.length > 0 ? 1 : 0));
const hasInventory = computed(() => inventories.value.length > 0);

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
  await getInventoriesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getInventoriesData = async () => {
  await inventoryStore.getInventoryListApi({
    search: search.value,
    sortBy: CREATED_AT,
    sortOrder: SortOrder.DESC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    type: inventoryType.value,
  });
};

const searchInventory = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getInventoriesData();
  }
};

const openViewInventoryModal = (inventory: Inventory) => {
  viewInventoryModal.value?.openViewInventoryModal(inventory);
};

const openInventoryOutModal = (inventory: Inventory) => {
  inventoryOutModal.value?.openInventoryOutModal(inventory);
};

const openDeleteInventoryModal = (inventory: Inventory) => {
  deleteModalRef.value?.open(
    { id: inventory.id, reference: `#${inventory.batchId} - ${inventory.product?.label}` },
    'Do you really want to delete the inventory',
  );
};

const deleteInventory = async (id: number) => {
  await inventoryStore.deleteInventoryApi(id);
};

const openAddInventory = (type: string) => {
  router.push({
    name: 'add-remove-inventory',
    params: { identifier: route.params.identifier, type: type },
  });
};

const saveInventoryOutEntry = async (formData: Ref<InventoryOutRequest>) => {
  await inventoryStore.inventoryOutApi(formData.value);
  basicNonStickyNotificationToggle();
  await getInventoriesData();
};

watch(inventoryType, async () => {
  await getInventoriesData();
});

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getInventoriesData();
});
</script>
