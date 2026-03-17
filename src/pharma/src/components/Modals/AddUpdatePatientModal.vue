<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">{{ modalTitle }}</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <PatientFormContent
        :form-data="formData"
        v-model:dob="dob"
        v-model:selected-patient-type="selectedPatientType"
        :date-options="dateOptions"
        :patient-types="patientTypes"
        :insurer-options="insurerOptions"
        :beneficiary-options="beneficiaryOptions"
        :default-insurer-option="defaultInsurerOption"
        :default-beneficiary-option="defaultBeneficiaryOption"
        @select-insurer="selectInsurer"
        @select-beneficiary="selectBeneficiary"
        @search-insurers="searchInsurers"
        @search-beneficiaries="searchBeneficiaries"
      />
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
import PatientFormContent from '@/components/PatientFormContent.vue';
import { computed, ref } from 'vue';
import { PatientRequest } from '@/composables/models/PatientRequest';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import { usePatientModalForm } from '@/composables/usePatientModalForm';

const emit = defineEmits(['submit']);

const {
  formData,
  patientId,
  selectedPatientType,
  patientTypes,
  insurerOptions,
  beneficiaryOptions,
  defaultInsurerOption,
  defaultBeneficiaryOption,
  openPatientModal: openForm,
  resetForm: resetFormFromComposable,
  selectInsurer,
  selectBeneficiary,
  searchInsurers,
  searchBeneficiaries,
} = usePatientModalForm();

const show = ref(false);
const dob = ref('');
const dateOptions = ref({
  showWeekNumbers: true,
  format: dateFormat,
  dropdowns: { minYear: 1900, maxYear: null, months: true, years: true },
});

const modalTitle = computed(() =>
  `${patientId.value ? 'Update' : 'Add'} Patient`,
);

async function openPatientModal(patientRequest: PatientRequest, id: number | null) {
  show.value = true;
  dob.value = id ? patientRequest.dob : '';
  await openForm(patientRequest, id);
}

function closeModal() {
  show.value = false;
  dob.value = '';
  resetFormFromComposable();
}

function handleSubmit() {
  formData.value.dob = dob.value;
  emit('submit', formData, patientId.value);
  closeModal();
}

defineExpose({ openPatientModal });
</script>
