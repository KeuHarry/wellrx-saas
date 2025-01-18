<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">{{ modalTitle }}</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-cols-1 gap-4 p-10">
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="first-name" class="block text-sm/6 text-gray-900"
              >First Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.firstName"
              type="text"
              placeholder="Enter first name"
              name="first-name"
              id="first-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="email" class="block text-sm/6 text-gray-900"
              >Last Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.lastName"
              type="text"
              placeholder="Enter last name"
              name="last-name"
              id="last-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="dob" class="block text-sm/6 text-gray-900">
              Date Of Birth <span class="text-danger">*</span>
            </label>
            <Litepicker
              v-model="dob"
              :options="dateOptions"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="phone" class="block text-sm/6 text-gray-900"
              >Phone Number <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.phone"
              type="text"
              placeholder="Ex. +233XXXXXXXXX"
              name="phone"
              id="phone"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="company" class="block text-sm/6 text-gray-900">
              Company Name<span class="text-danger">*</span>
            </label>
            <input
              v-model="formData.company"
              type="text"
              placeholder="Enter company name"
              name="company"
              id="company"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="email" class="block text-sm/6 text-gray-900"
              >Email <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.email"
              type="email"
              placeholder="patient@example.com"
              name="email"
              id="email"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-7 gap-2">
          <div class="col-span-2">
            <label for="city" class="block text-sm/6 text-gray-900">
              City<span class="text-danger">*</span>
            </label>
            <input
              v-model="formData.city"
              type="text"
              placeholder="Ex. Accra"
              name="city"
              id="city"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-5">
            <label for="address" class="block text-sm/6 text-gray-900">Address</label>
            <input
              v-model="formData.address"
              type="text"
              placeholder="Enter address"
              name="address"
              id="address"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="flex items-center gap-6 mt-5">
          <label class="block text-sm/6 text-gray-900">Patient is: </label>
          <div v-for="pt in patientTypes" :key="pt.id" class="space-x-2 text-center">
            <input
              type="radio"
              :value="pt.value"
              v-model="selectedPatientType"
              class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
            <label class="mr-4">{{ pt.name }}</label>
          </div>
        </div>
        <div>
          <label for="allergies" class="block text-sm/6 text-gray-900">Allergies</label>
          <textarea
            v-model="formData.allergies"
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="allergies"
            id="allergies"
          >
          </textarea>
        </div>
        <div>
          <label for="medecine-history" class="block text-sm/6 text-gray-900"
            >Medecine History</label
          >
          <textarea
            v-model="formData.medecineHistory"
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="medecine-history"
            id="medecine-history"
          >
          </textarea>
        </div>
      </div>
      <Dialog.Footer>
        <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20">Save</Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, ref } from 'vue';
import { PatientRequest } from '@/composables/models/PatientRequest';
import Litepicker from '@/components/Base/Litepicker';
import dayjs from 'dayjs';
import { dateFormat } from '@/constants/dateFormat';
import { COUNTER_CUSTOMER, patientType } from '@/constants/patientType';

const emit = defineEmits(['submit']);

const show = ref(false);
const patientId = ref<number | null>(null);
const dob = ref('');
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
const formData = ref<PatientRequest>({
  firstName: '',
  lastName: '',
  dob: dob.value,
  email: '',
  phone: '',
  company: '',
  address: '',
  city: '',
  insurerId: null,
  caretakerId: null,
  intermediaryId: null,
  allergies: '',
  medecineHistory: '',
});

const patientTypes = ref(patientType);
const selectedPatientType = ref<string>(COUNTER_CUSTOMER);

// COMPUTED
const modalTitle = computed(() => {
  return `${patientId.value ? 'Update' : 'Add'} Patient`;
});

// METHODS
const openPatientModal = (patientRequest: PatientRequest, id: number | null) => {
  patientId.value = id;
  show.value = true;
  formData.value = patientRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  formData.value.firstName = '';
  formData.value.lastName = '';
  formData.value.dob = '';
  formData.value.email = '';
  formData.value.phone = '';
  formData.value.company = '';
  formData.value.address = '';
  formData.value.city = '';
  formData.value.insurerId = null;
  formData.value.caretakerId = null;
  formData.value.intermediaryId = null;
  formData.value.allergies = '';
  formData.value.medecineHistory = '';
};

const handleSubmit = async () => {
  formData.value.dob = dob.value;
  emit('submit', formData, patientId.value);
  closeModal();
};

defineExpose({
  openPatientModal,
});
</script>
