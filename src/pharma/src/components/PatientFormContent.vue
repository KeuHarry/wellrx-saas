<template>
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
          :class="inputClass"
        />
      </div>
      <div class="col-span-3">
        <label for="last-name" class="block text-sm/6 text-gray-900"
          >Last Name <span class="text-danger">*</span></label
        >
        <input
          v-model="formData.lastName"
          type="text"
          placeholder="Enter last name"
          name="last-name"
          id="last-name"
          :class="inputClass"
        />
      </div>
    </div>
    <div class="grid grid-cols-6 gap-2">
      <div class="col-span-3">
        <label for="dob" class="block text-sm/6 text-gray-900">
          Date Of Birth <span class="text-danger">*</span>
        </label>
        <Litepicker
          :model-value="dob"
          @update:model-value="onDobUpdate"
          :options="dateOptions"
          :class="inputClass"
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
          :class="inputClass"
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
          :class="inputClass"
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
          :class="inputClass"
        />
      </div>
    </div>
    <div class="grid grid-cols-7 gap-2">
      <div class="col-span-2">
        <label for="city" class="block text-sm/6 text-gray-900">City<span class="text-danger">*</span></label>
        <input
          v-model="formData.city"
          type="text"
          placeholder="Ex. Accra"
          name="city"
          id="city"
          :class="inputClass"
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
          :class="inputClass"
        />
      </div>
    </div>
    <PatientTypeFields
      :selected-patient-type="selectedPatientType"
      @update:selected-patient-type="$emit('update:selectedPatientType', $event)"
      :patient-types="patientTypes"
      :insurer-options="insurerOptions"
      :beneficiary-options="beneficiaryOptions"
      :default-insurer-option="defaultInsurerOption"
      :default-beneficiary-option="defaultBeneficiaryOption"
      @select-insurer="$emit('selectInsurer', $event)"
      @select-beneficiary="$emit('selectBeneficiary', $event)"
      @search-insurers="$emit('searchInsurers', $event)"
      @search-beneficiaries="$emit('searchBeneficiaries', $event)"
    />
    <div>
      <label for="allergies" class="block text-sm/6 text-gray-900">Allergies</label>
      <textarea
        v-model="formData.allergies"
        :class="textareaClass"
        name="allergies"
        id="allergies"
      ></textarea>
    </div>
    <div>
      <label for="medecine-history" class="block text-sm/6 text-gray-900">Medecine History</label>
      <textarea
        v-model="formData.medecineHistory"
        :class="textareaClass"
        name="medecine-history"
        id="medecine-history"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
import Litepicker from '@/components/Base/Litepicker';
import PatientTypeFields from '@/components/PatientTypeFields.vue';
import { Option } from '@/composables/models/Option';
import { PatientRequest } from '@/composables/models/PatientRequest';

interface PatientTypeItem {
  id: number;
  value: string;
  name: string;
}

interface DateOptions {
  showWeekNumbers: boolean;
  format: string;
  dropdowns: { minYear: number; maxYear: number | null; months: boolean; years: boolean };
}

defineProps<{
  formData: PatientRequest;
  dob: string;
  dateOptions: DateOptions;
  selectedPatientType: string;
  patientTypes: PatientTypeItem[];
  insurerOptions: Option[];
  beneficiaryOptions: Option[];
  defaultInsurerOption: Option | null;
  defaultBeneficiaryOption: Option | null;
}>();

const emit = defineEmits<{
  (e: 'update:dob', value: string): void;
  (e: 'update:selectedPatientType', value: string): void;
  (e: 'selectInsurer', option: Option): void;
  (e: 'selectBeneficiary', option: Option): void;
  (e: 'searchInsurers', search: string): void;
  (e: 'searchBeneficiaries', search: string): void;
}>();

function onDobUpdate(value: string | null) {
  emit('update:dob', value ?? '');
}

const inputClass =
  'block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6';
const textareaClass =
  'block w-full border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6';
</script>
