import { computed, ref, watch } from 'vue';
import { PatientRequest } from '@/composables/models/PatientRequest';
import { Option } from '@/composables/models/Option';
import { Patient } from '@/composables/models/Patient';
import { Insurer } from '@/composables/models/Insurer';
import {
  BENEFICIARY,
  COUNTER_CUSTOMER,
  INSURED,
  patientType,
} from '@/constants/patientType';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';
import { LASTNAME, NAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import useInsurerStore from '@/stores/insurer';
import usePatientStore from '@/stores/patient';

const initialFormData = (): PatientRequest => ({
  firstName: '',
  lastName: '',
  dob: '',
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

/**
 * Dérive le type patient (Counter Customer / Insured / Beneficiary) à partir de insurerId et caretakerId.
 */
function derivePatientType(insurerId: number | null, caretakerId: number | null): string {
  if (insurerId != null) return INSURED;
  if (caretakerId != null) return BENEFICIARY;
  return COUNTER_CUSTOMER;
}

/**
 * Composable pour le formulaire du modal Add/Update Patient.
 * Gère formData, type patient, chargement assureurs/patients, et exclusion mutuelle insurerId/caretakerId.
 */
export function usePatientModalForm() {
  const insurerStore = useInsurerStore();
  const patientStore = usePatientStore();

  const formData = ref<PatientRequest>(initialFormData());
  const patientId = ref<number | null>(null);
  const selectedPatientType = ref<string>(COUNTER_CUSTOMER);

  const patientTypes = ref(patientType);

  /** Options pour le ComboBox assureur : liste des assureurs avec { id, name }. */
  const insurerOptions = computed<Option[]>(() =>
    insurerStore.getInsurers.map((i: Insurer) => ({ id: i.id, name: i.name })),
  );

  /** Options pour le ComboBox bénéficiaire : patients avec { id, name }, en excluant le patient courant en édition. */
  const beneficiaryOptions = computed<Option[]>(() =>
    patientStore
      .getPatients.filter((p: Patient) => p.id !== patientId.value)
      .map((p: Patient) => ({ id: p.id, name: `${p.lastName} ${p.firstName}` })),
  );

  /** Option par défaut pour l’assureur en mode édition. */
  const defaultInsurerOption = computed<Option | null>(() => {
    const id = formData.value.insurerId;
    if (id == null) return null;
    const insurer = insurerStore.getInsurers.find((i: Insurer) => i.id === id);
    return insurer ? { id: insurer.id, name: insurer.name } : null;
  });

  /** Option par défaut pour le bénéficiaire (caretaker) en mode édition. */
  const defaultBeneficiaryOption = computed<Option | null>(() => {
    const id = formData.value.caretakerId;
    if (id == null) return null;
    const patient = patientStore.getPatients.find((p: Patient) => p.id === id);
    return patient
      ? { id: patient.id, name: `${patient.lastName} ${patient.firstName}` }
      : null;
  });

  watch(selectedPatientType, (newType) => {
    if (newType === INSURED) formData.value.caretakerId = null;
    else if (newType === BENEFICIARY) formData.value.insurerId = null;
    else if (newType === COUNTER_CUSTOMER) {
      formData.value.insurerId = null;
      formData.value.caretakerId = null;
    }
  });

  /** Ouvre le modal : met à jour le formulaire, dérive le type et charge assureurs/patients. */
  async function openPatientModal(patientRequest: PatientRequest, id: number | null) {
    patientId.value = id;
    formData.value = { ...patientRequest };
    selectedPatientType.value = derivePatientType(
      patientRequest.insurerId,
      patientRequest.caretakerId,
    );
    await Promise.all([
      insurerStore.getInsurersApi({
        search: '',
        sortBy: NAME,
        sortOrder: SortOrder.ASC,
        offset: defaultOffset,
        limit: defaultItemsPerPage,
      }),
      patientStore.getPatientsApi({
        search: '',
        sortBy: LASTNAME,
        sortOrder: SortOrder.ASC,
        offset: defaultOffset,
        limit: defaultItemsPerPage,
      }),
    ]);
  }

  function resetForm() {
    formData.value = initialFormData();
    patientId.value = null;
    selectedPatientType.value = COUNTER_CUSTOMER;
  }

  function selectInsurer(insurer: Option) {
    formData.value.insurerId = insurer.id;
    formData.value.caretakerId = null;
  }

  function selectBeneficiary(patient: Option) {
    formData.value.caretakerId = patient.id;
    formData.value.insurerId = null;
  }

  async function searchInsurers(search: string) {
    await insurerStore.getInsurersApi({
      search,
      sortBy: NAME,
      sortOrder: SortOrder.ASC,
      offset: defaultOffset,
      limit: defaultItemsPerPage,
    });
  }

  async function searchBeneficiaries(search: string) {
    await patientStore.getPatientsApi({
      search,
      sortBy: LASTNAME,
      sortOrder: SortOrder.ASC,
      offset: defaultOffset,
      limit: defaultItemsPerPage,
    });
  }

  return {
    formData,
    patientId,
    selectedPatientType,
    patientTypes,
    insurerOptions,
    beneficiaryOptions,
    defaultInsurerOption,
    defaultBeneficiaryOption,
    openPatientModal,
    resetForm,
    selectInsurer,
    selectBeneficiary,
    searchInsurers,
    searchBeneficiaries,
  };
}
