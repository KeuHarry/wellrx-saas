<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Labels</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openLabelCodeModal()" variant="primary" class="mr-2 shadow-md">
        Add Label
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
        Showing {{ startLabelCount }} to {{ paginate }} of {{ labelCodeTotal }} entries
      </div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-72 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchLabel"
            type="text"
            class="w-72 pr-10 !box"
            placeholder="Search by label..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">LABEL</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(labelCode, ckey) in labelCodes"
            :key="ckey"
            class="intro-x"
            @click="openLabelCodeModal(labelCode)"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ labelCode.labelCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ labelCode.label }}
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
                    @click="openDeleteLabelCodeModal(labelCode)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasLabel">
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
      :totalItems="labelCodeTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteLabelCode" />
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
  <AddUpdateLabelCodeModal ref="labelCodeModal" @submit="createUpdateLabelCode" />
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Button from '@/components/Base/Button';
import Pagination from '@/components/Base/Pagination';
import { FormInput, FormSelect } from '@/components/Base/Form';
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
import AddUpdateLabelCodeModal from '@/components/Modals/AddUpdateLabelCodeModal.vue';
import useLabelCodeStore from '@/stores/labelCode';
import { LabelCode } from '@/composables/models/LabelCode';
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import { LabelCodeRequest } from '@/composables/models/LabelCodeRequest';
import SortOrder from '@/enums/sortOrder';
import { LABEL } from '@/constants/properties';
import PaginationComponent from '@/components/PaginationComponent.vue';

const route = useRoute();
const labelCodeModal = ref<InstanceType<typeof AddUpdateLabelCodeModal> | null>(null);
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
const labelCodeStore = useLabelCodeStore();
const toastStore = useToastStore();

// COMPUTED
const labelCodes = computed(() => labelCodeStore.getLabelCodes);
const labelCodeTotal = computed(() => labelCodeStore.getLabelCodeTotal);
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

const startLabelCount = computed(() => (labelCodes.value.length > 0 ? 1 : 0));
const hasLabel = computed(() => labelCodes.value.length > 0);

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
  await getLabelCodesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getLabelCodesData = async () => {
  await labelCodeStore.getLabelCodesApi({
    search: search.value,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchLabel = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getLabelCodesData();
  }
};

const openLabelCodeModal = (labelCode: LabelCode | null = null) => {
  labelCodeModal.value?.openLabelModal(
    {
      labelCode: labelCode ? labelCode.labelCode : '',
      label: labelCode ? labelCode.label : '',
    },
    labelCode ? labelCode.id : null,
  );
};

const createUpdateLabelCode = async (
  formData: Ref<LabelCodeRequest>,
  labelCodeId: number | null,
) => {
  if (labelCodeId) {
    await labelCodeStore.updateLabelCodeApi(labelCodeId, formData.value);
  } else {
    await labelCodeStore.createLabelCodeApi(formData.value);
  }
  basicNonStickyNotificationToggle();
  await getLabelCodesData();
};

const openDeleteLabelCodeModal = (labelCode: LabelCode) => {
  deleteModalRef.value?.open(
    { id: labelCode.id, reference: labelCode.labelCode },
    'Do you really want to delete the label',
  );
};

const deleteLabelCode = async (id: number) => {
  await labelCodeStore.deleteLabelCodeApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getLabelCodesData();
});
</script>
