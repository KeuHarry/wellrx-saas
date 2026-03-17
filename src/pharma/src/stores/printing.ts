import { defineStore } from 'pinia';
import { usePrintInvoice } from '@/composables/api/printing/usePrintInvoice';
import { usePrintProforma } from '@/composables/api/printing/usePrintProforma';
import { usePrintPrescriptionLabels } from '@/composables/api/printing/usePrintPrescriptionLabels';

type PrintingState = {
  isPending: boolean;
};

export const usePrintingStore = defineStore('printing-store', {
  state: (): PrintingState => ({
    isPending: false,
  }),

  getters: {},

  actions: {
    printInvoiceApi(id: number, template: string) {
      const { printPreview } = usePrintInvoice();

      try {
        this.isPending = true;
        printPreview(id, template);
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async printProformaApi(id: number, template: string) {
      const { printPreview } = usePrintProforma();

      try {
        this.isPending = true;
        printPreview(id, template);
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    printLabelsApi(prescriptionId: number) {
      const { printLabels } = usePrintPrescriptionLabels();
      try {
        this.isPending = true;
        printLabels(prescriptionId);
      } catch (error) {
        this.isPending = false;
        throw error;
      } finally {
        this.isPending = false;
      }
    },
  },
});

export default usePrintingStore;
