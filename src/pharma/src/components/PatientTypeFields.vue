<template>
  <div>
    <div class="flex items-center gap-6 mt-5">
      <label class="block text-sm/6 text-gray-900">Patient is: </label>
      <div v-for="pt in patientTypes" :key="pt.id" class="space-x-2 text-center">
        <input
          type="radio"
          name="patientType"
          :value="pt.value"
          :checked="selectedPatientType === pt.value"
          @change="onTypeChange(pt.value)"
          class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
        <label class="mr-4">{{ pt.name }}</label>
      </div>
    </div>
    <div v-if="selectedPatientType === INSURED" class="mt-2">
      <label class="block text-sm/6 text-gray-900 mb-1">Insurer</label>
      <ComboBox
        :options="insurerOptions"
        @search="$emit('searchInsurers', $event)"
        @selected="$emit('selectInsurer', $event)"
        :defaultOption="defaultInsurerOption"
        placeholder="Select insurer"
      />
    </div>
    <div v-if="selectedPatientType === BENEFICIARY" class="mt-2">
      <label class="block text-sm/6 text-gray-900 mb-1">Caretaker</label>
      <ComboBox
        :options="beneficiaryOptions"
        @search="$emit('searchBeneficiaries', $event)"
        @selected="$emit('selectBeneficiary', $event)"
        :defaultOption="defaultBeneficiaryOption"
        placeholder="Select beneficiary"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import ComboBox from '@/components/ComboBox.vue';
import { Option } from '@/composables/models/Option';
import { BENEFICIARY, INSURED } from '@/constants/patientType';

interface PatientTypeItem {
  id: number;
  value: string;
  name: string;
}

defineProps<{
  patientTypes: PatientTypeItem[];
  selectedPatientType: string;
  insurerOptions: Option[];
  beneficiaryOptions: Option[];
  defaultInsurerOption: Option | null;
  defaultBeneficiaryOption: Option | null;
}>();

const emit = defineEmits<{
  (e: 'update:selectedPatientType', value: string): void;
  (e: 'selectInsurer', option: Option): void;
  (e: 'selectBeneficiary', option: Option): void;
  (e: 'searchInsurers', search: string): void;
  (e: 'searchBeneficiaries', search: string): void;
}>();

/** Émet le type patient choisi pour que le parent mette à jour selectedPatientType (un seul radio actif). */
function onTypeChange(value: string) {
  emit('update:selectedPatientType', value);
}
</script>
