import { SearchParams } from '@/composables/models/SearchParams';
import { PRESCRIPTION_DATE } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { PrescriptionFragment } from '@/composables/models/PrescriptionFragment';
import { Prescription } from '@/composables/models/Prescription';
import useGetPrescriptions from '@/composables/api/prescriptions/useGetPrescriptions';
import useDeletePrescription from '@/composables/api/prescriptions/useDeletePrescription';
import useGetPrescription from '@/composables/api/prescriptions/useGetPrescription';
import { PrescriptionRequest } from '@/composables/models/PrescriptionRequest';
import useCreatePrescription from '@/composables/api/prescriptions/useCreatePrescription';
import useUpdatePrescription from '@/composables/api/prescriptions/useUpdatePrescription';

type PrescriptionState = {
  prescriptions: PrescriptionFragment[];
  prescriptionTotal: number;
  prescription: Prescription | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const usePrescriptionStore = defineStore('prescription-store', {
  state: (): PrescriptionState => ({
    prescriptions: [],
    prescriptionTotal: 0,
    prescription: null,
    searchParams: {
      search: '',
      sortBy: PRESCRIPTION_DATE,
      sortOrder: SortOrder.DESC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getPrescriptions: (state) => state.prescriptions,
    getPrescription: (state) => state.prescription,
    getPrescriptionTotal: (state) => state.prescriptionTotal,
  },

  actions: {
    async getPrescriptionsApi(searchParams: SearchParams | null = null) {
      const getPrescriptions = useGetPrescriptions(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getPrescriptions();
        this.prescriptions = response.data ?? [];
        this.prescriptionTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createPrescriptionApi(prescriptionRequest: PrescriptionRequest) {
      const createPrescription = useCreatePrescription();
      try {
        this.isPending = true;
        const response = await createPrescription(prescriptionRequest);
        this.prescription = response.data;
        toastStore.setMessage('Prescription successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updatePrescriptionApi(id: number, prescriptionRequest: PrescriptionRequest) {
      const udpatePrescription = useUpdatePrescription();
      try {
        this.isPending = true;
        const response = await udpatePrescription(id, prescriptionRequest);
        toastStore.setMessage('Prescription successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deletePrescriptionApi(id: number) {
      const deletePrescription = useDeletePrescription();
      try {
        this.isPending = true;
        const response = await deletePrescription(id);
        if (response.success) {
          this.prescriptions = this.prescriptions.filter((prescription) => prescription.id !== id);
        }
        toastStore.setMessage('Prescription successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getPrescriptionApi(id: number) {
      const getPrescription = useGetPrescription();
      try {
        this.isPending = true;
        const response = await getPrescription(id);
        if (response.success) {
          this.prescription = response.data;
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default usePrescriptionStore;
