<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Doctors</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openPrescriberModal()" variant="primary" class="mr-2 shadow-md">
        Add Doctor
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
      <div class="hidden mx-auto md:block text-slate-500">
        Showing {{ startPrescriberCount }} to {{ paginate }} of {{ prescriberTotal }} entries
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchPrescriber"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">NAME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PHONE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">REGISTRATION NUMBER</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">ADDRESS</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(prescriber, ckey) in prescribers"
            :key="ckey"
            class="intro-x"
            @click="openPrescriberModal(prescriber)"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescriber.firstName + ' ' + prescriber.lastName }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescriber.phone }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescriber.registrationNumber }}
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescriber.address }}
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
                    @click="openDeletePrescriberModal(prescriber)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasPrescriber">
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
      :totalItems="prescriberTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deletePrescriber" />
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
  <AddUpdatePrescriberModal ref="prescriberModal" @submit="createUpdatePrescriber" />
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
import usePrescriberStore from '@/stores/prescriber';
import AddUpdatePrescriberModal from '@/components/Modals/AddUpdatePrescriberModal.vue';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import { Prescriber } from '@/composables/models/Prescriber';
import { Ref } from 'vue';
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';
import PaginationComponent from '@/components/PaginationComponent.vue';
import { FIRSTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';

const prescriberModal = ref<InstanceType<typeof AddUpdatePrescriberModal> | null>(null);
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
const prescriberStore = usePrescriberStore();
const toastStore = useToastStore();

// COMPUTED
const prescribers = computed(() => prescriberStore.getPrescribers);
const prescriberTotal = computed(() => prescriberStore.getPrescriberTotal);
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

const startPrescriberCount = computed(() => (prescribers.value.length > 0 ? 1 : 0));
const hasPrescriber = computed(() => prescribers.value.length > 0);

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
  await getPrescribersData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getPrescribersData = async () => {
  await prescriberStore.getPrescribersApi({
    search: search.value,
    sortBy: FIRSTNAME,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchPrescriber = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getPrescribersData();
  }
};

const openPrescriberModal = (prescriber: Prescriber | null = null) => {
  prescriberModal.value?.openPrescriberModal(
    {
      firstName: prescriber ? prescriber.firstName : '',
      lastName: prescriber ? prescriber.lastName : '',
      phone: prescriber ? prescriber.phone : '',
      registrationNumber: prescriber ? prescriber.registrationNumber : '',
      address: prescriber ? prescriber.address : '',
    },
    prescriber ? prescriber.id : null,
  );
};

const createUpdatePrescriber = async (
  formData: Ref<PrescriberRequest>,
  prescriberId: number | null,
) => {
  if (prescriberId) {
    await prescriberStore.updatePrescriberApi(prescriberId, formData.value);
  } else {
    await prescriberStore.createPrescriberApi(formData.value);
  }
  basicNonStickyNotificationToggle();
  await getPrescribersData();
};

const openDeletePrescriberModal = (prescriber: Prescriber) => {
  deleteModalRef.value?.open(
    { id: prescriber.id, reference: prescriber.registrationNumber },
    'Do you really want to delete the doctor with reg. number',
  );
};

const deletePrescriber = async (id: number) => {
  await prescriberStore.deletePrescriberApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getPrescribersData();
});
</script>
