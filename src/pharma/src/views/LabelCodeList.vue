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
      <div class="hidden mx-auto md:block text-slate-500">Showing 1 to 10 of 150 entries</div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-72 text-slate-500">
          <FormInput type="text" class="w-72 pr-10 !box" placeholder="Search..." />
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
        </Table.Tbody>
      </Table>
    </div>
    <!-- END: Data List -->
    <!-- BEGIN: Pagination -->
    <div class="flex flex-wrap items-center col-span-12 intro-y sm:flex-row sm:flex-nowrap">
      <Pagination class="w-full sm:w-auto sm:mr-auto">
        <Pagination.Link>
          <Lucide icon="ChevronsLeft" class="w-4 h-4" />
        </Pagination.Link>
        <Pagination.Link>
          <Lucide icon="ChevronLeft" class="w-4 h-4" />
        </Pagination.Link>
        <Pagination.Link>...</Pagination.Link>
        <Pagination.Link active>1</Pagination.Link>
        <Pagination.Link>2</Pagination.Link>
        <Pagination.Link>3</Pagination.Link>
        <Pagination.Link>...</Pagination.Link>
        <Pagination.Link>
          <Lucide icon="ChevronRight" class="w-4 h-4" />
        </Pagination.Link>
        <Pagination.Link>
          <Lucide icon="ChevronsRight" class="w-4 h-4" />
        </Pagination.Link>
      </Pagination>
      <FormSelect class="w-20 mt-3 !box sm:mt-0">
        <option>10</option>
        <option>25</option>
        <option>35</option>
        <option>50</option>
      </FormSelect>
    </div>
    <!-- END: Pagination -->
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

// STORE
const tenantStore = useTenantStore();
const labelCodeStore = useLabelCodeStore();
const toastStore = useToastStore();

// COMPUTED
const labelCodes = computed(() => labelCodeStore.getLabelCodes);
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

//METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getLabelCodesData = async () => {
  await labelCodeStore.getLabelCodesApi();
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
  await getLabelCodesData();
});
</script>
