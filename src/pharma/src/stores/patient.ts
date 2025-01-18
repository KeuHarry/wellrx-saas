import { SearchParams } from '@/composables/models/SearchParams';
import { FIRSTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Patient } from '@/composables/models/Patient';
import useGetPatients from '@/composables/api/patients/useGetPatients';
import { PatientRequest } from '@/composables/models/PatientRequest';
import useCreatePatient from '@/composables/api/patients/useCreatePatient';
import useUpdatePatient from '@/composables/api/patients/useUpdatePatient';
import useDeletePatient from '@/composables/api/patients/useDeletePatient';

type PatientState = {
  patients: Patient[];
  patientTotal: number;
  patient: Patient | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const usePatientStore = defineStore('patient-store', {
  state: (): PatientState => ({
    patients: [],
    patientTotal: 0,
    patient: null,
    searchParams: {
      search: '',
      sortBy: FIRSTNAME,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getPatients: (state) => state.patients,
    getPatient: (state) => state.patient,
    getPatientTotal: (state) => state.patientTotal,
  },

  actions: {
    async getPatientsApi(searchParams: SearchParams | null = null) {
      const getPatients = useGetPatients(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getPatients();
        this.patients = response.data ?? [];
        this.patientTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createPatientApi(patientRequest: PatientRequest) {
      const createPatient = useCreatePatient();
      try {
        this.isPending = true;
        const response = await createPatient(patientRequest);
        this.patient = response.data;
        toastStore.setMessage('Patient successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updatePatientApi(id: number, patientRequest: PatientRequest) {
      const udpatePatient = useUpdatePatient();
      try {
        this.isPending = true;
        const response = await udpatePatient(id, patientRequest);
        toastStore.setMessage('Patient successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deletePatientApi(id: number) {
      const deletePatient = useDeletePatient();
      try {
        this.isPending = true;
        const response = await deletePatient(id);
        if (response.success) {
          this.patients = this.patients.filter((patient) => patient.id !== id);
        }
        toastStore.setMessage('Patient successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default usePatientStore;
