<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Prescriptions</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="relative z-10 flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openAddEditPrescription()" variant="primary" class="mr-2 shadow-md">
        <span>Add Prescription</span>
      </Button>
      <div class="hidden mx-auto md:block text-slate-500"></div>
      <div class="mr-3 h-9">
        <select
          v-model="prescriptionStatus"
          class="h-9 border border-gray-300 rounded-md shadow-sm text-sm"
          name="sale-status"
          id="sale-status"
        >
          <option
            v-for="(type, ikey) in prescriptionStatusList"
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
            @input="searchPrescription"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by code, patient or prescriber..."
          />
          <Lucide icon="Search" class="absolute inset-y-0 right-0 w-4 h-4 my-auto mr-3" />
        </div>
      </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="relative z-0 col-span-12 overflow-auto intro-y lg:overflow-visible">
      <Table class="border-spacing-y-[4px] border-separate -mt-2">
        <Table.Thead>
          <Table.Tr>
            <Table.Th class="border-b-0 whitespace-nowrap">CODE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PATIENT</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PRESCRIBER</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">TOTAL / NET DUE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">STATUS</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">DATE</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(prescription, ckey) in prescriptions"
            :key="ckey"
            @click="openAddEditPrescription(prescription.id)"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescription.prescriptionCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ `${prescription.patient.lastName} ${prescription.patient.firstName}` }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ `${prescription.prescriber.lastName} ${prescription.prescriber.firstName}` }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ `${formatPrice(prescription.total)} / ${formatPrice(prescription.netDue)}` }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              <span
                class="text-xs border rounded-full px-4 py-1 bg-opacity-20 border-opacity-5"
                :class="{
                  'bg-green-600 border-green-600 text-green-600':
                    prescription.status === PrescriptionStatus.COMPLETED,
                  'bg-red-600 border-red-600 text-red-600':
                    prescription.status === PrescriptionStatus.IN_PROGRESS,
                  'bg-blue-800 border-blue-800 text-blue-800':
                    prescription.status === PrescriptionStatus.PAID,
                }"
              >
                {{ getSaleStatus(prescription.status) }}
              </span>
            </Table.Td>
            <Table.Td
              class="box w-44 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ prescription.prescriptionDate }}
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
                  v-if="prescription.status === PrescriptionStatus.IN_PROGRESS"
                  class="flex items-center text-danger"
                  href="#"
                  @click="openDeletePrescriptionModal(prescription)"
                >
                  <Tippy content="Delete">
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </Tippy>
                </a>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasPrescription">
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
      :totalItems="prescriptionTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deletePrescription" />
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
import { computed, onMounted, provide, ref } from 'vue';
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
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import SortOrder from '@/enums/sortOrder';
import { CREATED_AT, PRESCRIPTION_DATE } from '@/constants/properties';
import { formatPrice, getSaleStatus } from '@/utils/helper';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import { watch } from 'vue';
import { prescriptionStatusList } from '@/constants/prescriptionStatusList';
import usePrescriptionStore from '@/stores/prescription';
import { PrescriptionFragment } from '@/composables/models/PrescriptionFragment';
import PrescriptionStatus from '@/enums/prescriptionStatus';

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
const prescriptionStatus = ref<number | null>(null);
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
const prescriptionStore = usePrescriptionStore();
const toastStore = useToastStore();

// COMPUTED
const prescriptions = computed(() => prescriptionStore.getPrescriptions);
const prescriptionTotal = computed(() => prescriptionStore.getPrescriptionTotal);
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

const hasPrescription = computed(() => prescriptions.value.length > 0);

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
  await getPrescriptionsData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getPrescriptionsData = async () => {
  await prescriptionStore.getPrescriptionsApi({
    search: search.value,
    sortBy: PRESCRIPTION_DATE,
    sortOrder: SortOrder.DESC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
    status: prescriptionStatus.value,
    startDate: fromDate.value,
    endDate: toDate.value,
  });
};

const searchPrescription = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getPrescriptionsData();
  }
};

const openDeletePrescriptionModal = (prescription: PrescriptionFragment) => {
  deleteModalRef.value?.open(
    { id: prescription.id, reference: prescription.prescriptionCode },
    'Do you really want to delete the prescription',
  );
};

const deletePrescription = async (id: number) => {
  await prescriptionStore.deletePrescriptionApi(id);
  await getPrescriptionsData();
};

const openAddEditPrescription = (prescriptionId: number | null = null) => {
  if (prescriptionId) {
    router.push({
      name: 'edit-prescription',
      params: { identifier: route.params.identifier, id: prescriptionId },
    });
  } else {
    router.push({
      name: 'add-prescription',
      params: { identifier: route.params.identifier },
    });
  }
};

watch([prescriptionStatus, fromDate, toDate], () => {
  currentPage.value = 1;
  getPrescriptionsData();
});

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getPrescriptionsData();
});
</script>
