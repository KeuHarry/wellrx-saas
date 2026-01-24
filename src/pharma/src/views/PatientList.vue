<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Patients</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openPatientModal()" variant="primary" class="mr-2 shadow-md">
        Add Patient
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
            @input="searchPatient"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search by name, code or email..."
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
            <Table.Th class="border-b-0 whitespace-nowrap">EMAIL</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PHONE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">ADDRESS</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">CITY</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(patient, ckey) in patients"
            :key="ckey"
            @click="openPatientModal(patient)"
          >
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.patientCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.firstName + ' ' + patient.lastName }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.email }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.phone }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.address }}
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ patient.city }}
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
                    @click="openDeletePatientModal(patient)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasPatient">
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
      :totalItems="patientTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deletePatient" />
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
  <AddUpdatePatientModal ref="patientModal" @submit="createUpdatePatient" />
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
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import { FIRSTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import AddUpdatePatientModal from '@/components/Modals/AddUpdatePatientModal.vue';
import { Patient } from '@/composables/models/Patient';
import { PatientRequest } from '@/composables/models/PatientRequest';
import usePatientStore from '@/stores/patient';

const patientModal = ref<InstanceType<typeof AddUpdatePatientModal> | null>(null);
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
const patientStore = usePatientStore();
const toastStore = useToastStore();

// COMPUTED
const patients = computed(() => patientStore.getPatients);
const patientTotal = computed(() => patientStore.getPatientTotal);
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

const startPatientCount = computed(() => (patients.value.length > 0 ? 1 : 0));
const hasPatient = computed(() => patients.value.length > 0);

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
  await getPatientsData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getPatientsData = async () => {
  await patientStore.getPatientsApi({
    search: search.value,
    sortBy: FIRSTNAME,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchPatient = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getPatientsData();
  }
};

const openPatientModal = (patient: Patient | null = null) => {
  patientModal.value?.openPatientModal(
    {
      firstName: patient ? patient.firstName : '',
      lastName: patient ? patient.lastName : '',
      dob: patient ? patient.dob : '',
      phone: patient ? patient.phone : '',
      email: patient ? patient.email : '',
      company: patient ? patient.company : '',
      address: patient ? patient.address : '',
      city: patient ? patient.city : '',
      insurerId: patient ? patient.insurerId : null,
      caretakerId: patient ? patient.caretakerId : null,
      intermediaryId: patient ? patient.intermediaryId : null,
      allergies: patient ? patient.allergies : '',
      medecineHistory: patient ? patient.medecineHistory : '',
    },
    patient ? patient.id : null,
  );
};

const createUpdatePatient = async (formData: Ref<PatientRequest>, patientId: number | null) => {
  if (patientId) {
    await patientStore.updatePatientApi(patientId, formData.value);
  } else {
    await patientStore.createPatientApi(formData.value);
  }
  basicNonStickyNotificationToggle();
  await getPatientsData();
};

const openDeletePatientModal = (patient: Patient) => {
  deleteModalRef.value?.open(
    { id: patient.id, reference: patient.patientCode },
    'Do you really want to delete the patient',
  );
};

const deletePatient = async (id: number) => {
  await patientStore.deletePatientApi(id);
  basicNonStickyNotificationToggle();
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getPatientsData();
});
</script>
