import { SearchParams } from '@/composables/models/SearchParams';
import { LABEL } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import useUpdateLabelCode from '@/composables/api/labelCodes/useUpdateLabelCode';
import { LabelCodeRequest } from '@/composables/models/LabelCodeRequest';
import useCreateLabelCode from '@/composables/api/labelCodes/useCreateLabelCode';
import useDeleteLabelCode from '@/composables/api/labelCodes/useDeleteLabelCode';
import { LabelCode } from '@/composables/models/LabelCode';
import useGetLabelCodes from '@/composables/api/labelCodes/useGetLabelCodes';

type LabelCodeState = {
  labelCodes: LabelCode[];
  labelCodeTotal: number;
  labelCode: LabelCode | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useLabelCodeStore = defineStore('label-code-store', {
  state: (): LabelCodeState => ({
    labelCodes: [],
    labelCodeTotal: 0,
    labelCode: null,
    searchParams: {
      search: '',
      sortBy: LABEL,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getLabelCodes: (state) => state.labelCodes,
    getLabelCode: (state) => state.labelCode,
    getLabelCodeTotal: (state) => state.labelCodeTotal,
  },

  actions: {
    async getLabelCodesApi() {
      const getLabelCodes = useGetLabelCodes(this.searchParams);
      try {
        this.isPending = true;
        const response = await getLabelCodes();
        this.labelCodes = response.data ?? [];
        this.labelCodeTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createLabelCodeApi(labelCodeRequest: LabelCodeRequest) {
      const createLabelCode = useCreateLabelCode();
      try {
        this.isPending = true;
        const response = await createLabelCode(labelCodeRequest);
        this.labelCode = response.data;
        toastStore.setMessage('Label successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateLabelCodeApi(id: number, labelCodeRequest: LabelCodeRequest) {
      const udpateLabelCode = useUpdateLabelCode();
      try {
        this.isPending = true;
        const response = await udpateLabelCode(id, labelCodeRequest);
        toastStore.setMessage('Label successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteLabelCodeApi(id: number) {
      const deleteLabelCode = useDeleteLabelCode();
      try {
        this.isPending = true;
        const response = await deleteLabelCode(id);
        if (response.success) {
          this.labelCodes = this.labelCodes.filter((labelCode) => labelCode.id !== id);
        }
        toastStore.setMessage('Label successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useLabelCodeStore;
