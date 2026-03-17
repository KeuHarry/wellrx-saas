<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="flex col-span-12 h-14 px-5 justify-between">
      <div class="flex items-center">
        <h2 class="text-lg font-medium text-slate-900 mr-1">Prescription Form</h2>
        <span v-if="prescription"> - {{ prescription.prescriptionCode }}</span>
        <span
          v-if="prescription"
          class="border rounded-full text-xs px-4 py-1 ml-1 bg-opacity-20 border-opacity-5"
          :class="{
            'bg-red-600 border-red-600 text-red-600': isInProgress,
            'bg-green-600 border-green-600 text-green-600': isCompleted,
            'bg-blue-800 border-blue-800 text-blue-800': isPaid,
          }"
          >{{ prescriptionStatus }}
        </span>
      </div>
      <div class="flex items-center">
        <Menu v-if="prescription">
          <Tippy content="Print">
            <Menu.Button :as="Button" class="px-2 !box">
              <span class="flex items-center justify-center w-5 h-5">
                <Lucide icon="Printer" class="w-4 h-4" />
              </span>
            </Menu.Button>
          </Tippy>
          <Menu.Items class="w-40">
            <Menu.Item @click="printProforma"
              ><Lucide icon="FileDown" class="w-4 h-4 mr-2" /> Proforma Invoice</Menu.Item
            >
            <Menu.Item v-if="isPaid" @click="printInvoice"
              ><Lucide icon="FileDown" class="w-4 h-4 mr-2" />Invoice</Menu.Item
            >
            <Menu.Item v-if="isCompleteOrPaid" @click="printLabels"
              ><Lucide icon="FileDown" class="w-4 h-4 mr-2" />Labels</Menu.Item
            >
          </Menu.Items>
        </Menu>
        <Button
          v-if="!isCompleteOrPaid"
          :disabled="!canSavePrescription"
          variant="primary"
          @click="savePrescription(PrescriptionStatus.IN_PROGRESS)"
          class="ml-3 disabled:cursor-not-allowed"
          >Save</Button
        >
        <Button
          v-if="!isCompleteOrPaid"
          @click="savePrescription(PrescriptionStatus.COMPLETED)"
          :disabled="!canComplete"
          class="hover:bg-teal-600/15 bg-teal-600 hover:border-teal-600/10 border-teal-600 text-teal-600 bg-opacity-20 border-opacity-5 ml-3"
          >Complete</Button
        >
        <Button class="ml-3" variant="outline-secondary" @click="closeAddEditPrescriptionForm"
          >Cancel</Button
        >
      </div>
    </div>
    <div class="grid grid-cols-12 gap-3 m-5">
      <div v-if="!isCompleteOrPaid" class="col-span-12 bg-white border rounded-lg shadow-md">
        <div class="flex col-span-12 gap-2 p-5">
          <div class="w-[60%]">
            <label for="product-name" class="block text-sm/6 text-gray-600"
              >Product <span class="text-danger">*</span></label
            >
            <ComboBox
              :options="products"
              @search="searchProduct"
              @selected="selectProduct"
              :defaultOption="selectedProduct"
              placeholder="Select product"
            />
          </div>
          <div class="w-[9%]">
            <label for="qoh" class="block text-sm/6 text-gray-600">QOH</label>
            <input
              v-model="productQoh"
              type="number"
              min="1"
              placeholder="Ex. 0"
              name="qoh"
              id="qoh"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="w-[9%]">
            <label for="qty" class="block text-sm/6 text-gray-600"
              >Quantity <span class="text-danger">*</span></label
            >
            <input
              v-model="prescriptionItemForm.quantity"
              type="number"
              min="1"
              placeholder="Ex. 0"
              name="qty"
              id="qty"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="days" class="block text-sm/6 text-gray-600">Days</label>
            <input
              v-model="prescriptionItemForm.courseDays"
              type="number"
              placeholder="Enter days"
              name="acq-cost"
              id="acq-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="refills" class="block text-sm/6 text-gray-600">Refills</label>
            <input
              v-model="prescriptionItemForm.refillAllowedTimes"
              type="number"
              placeholder="Enter refills"
              name="refills"
              id="refills"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="w-[12%]">
            <label for="tax" class="block text-sm/6 text-gray-600">Tax (per item)</label>
            <input
              v-model="prescriptionItemForm.tax"
              type="number"
              placeholder="Enter tax"
              name="tax"
              id="tax"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="flex col-span-1 items-end justify-end">
            <Button variant="primary" class="w-10 h-10 px-0 rounded-full" @click="addProduct">
              <Lucide icon="Plus" class="w-6 h-6" />
            </Button>
          </div>
        </div>
        <div class="flex col-span-12 gap-2 px-5 pb-5">
          <div class="w-[15%]">
            <label for="fee" class="block text-sm/6 text-gray-600">Fee</label>
            <ComboBox
              :options="fees"
              @search="searchFee"
              @selected="selectFee"
              placeholder="Select fee"
            />
          </div>
          <div class="w-[85%]">
            <label for="label-code" class="block text-sm/6 text-gray-600">Labels</label>
            <ComboBox
              :options="labelCodes"
              @selected="selectLabels"
              :defaultOptions="selectedLabels"
              placeholder="Select label"
              multiple
            />
          </div>
        </div>
        <div class="flex col-span-12 gap-4 px-5 pb-5">
          <p>
            Price: <span class="font-medium mr-3">{{ prescriptionItemForm.cost }}</span> Tax:
            <span class="font-medium mr-3">{{ prescriptionItemForm.tax }}</span> Fee:
            <span class="font-medium mr-3">{{ prescriptionItemForm.fee }}</span> Total:
            <span class="font-medium">{{ formatPrice(prescriptionItemSubTotal) }}</span>
          </p>
        </div>
      </div>
      <div class="col-span-12 px-5 bg-white border rounded-lg shadow-md">
        <ul v-if="prescriptionItemsData.length > 0">
          <li
            v-for="(prescriptionItem, index) in prescriptionItemsData"
            :key="index"
            class="flex items-center space-x-2 py-5 border-b border-gray-200"
          >
            <div class="w-[40%] text-slate-900">
              <span>{{ prescriptionItem.productName }}</span>
              <div class="flex items-center">
                <div
                  v-for="(lCode, index) in prescriptionItem.labelCodes"
                  :key="index"
                  class="bg-indigo-100 text-indigo-700 text-sm rounded-md px-2 py-1 mr-2"
                >
                  {{ lCode.code }}
                </div>
              </div>
            </div>
            <div>
              <label for="quantity" class="block text-sm/6 text-gray-600">Quantity</label>
              <input
                v-model="prescriptionItem.quantity"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="quantity"
                id="quantity"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="quantity" class="block text-sm/6 text-gray-600">Days</label>
              <input
                v-model="prescriptionItem.courseDays"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="course-days"
                id="course-days"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="refills" class="block text-sm/6 text-gray-600">Refills</label>
              <input
                v-model="prescriptionItem.refillAllowedTimes"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="refills"
                id="refills"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="price" class="block text-sm/6 text-gray-600">Price</label>
              <input
                v-model="prescriptionItem.cost"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="price"
                id="price"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="tax" class="block text-sm/6 text-gray-600">Tax</label>
              <input
                v-model="prescriptionItem.tax"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="tax"
                id="tax"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="fee" class="block text-sm/6 text-gray-600">Fee</label>
              <input
                v-model="prescriptionItem.fee"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="fee"
                id="fee"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div>
              <label for="item-total" class="block text-sm/6 text-gray-600">Total</label>
              <input
                v-model="prescriptionItem.total"
                type="number"
                min="1"
                placeholder="Ex. 0"
                name="item-total"
                id="item-total"
                class="block w-full h-8 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :disabled="isCompleteOrPaid"
              />
            </div>
            <div v-if="!isCompleteOrPaid" class="w-[5%]">
              <a
                @click="removeItem(index)"
                class="flex items-center justify-end text-danger cursor-pointer"
              >
                <Lucide icon="XIcon" class="w-6 h-6" />
              </a>
            </div>
          </li>
        </ul>
        <div v-else class="flex p-5 items-center justify-center">
          <h2 class="text-gray-400">No product selected</h2>
        </div>
      </div>
      <div class="col-span-4 p-5 bg-white border rounded-lg shadow-md">
        <h3 class="font-medium pb-3">Patient Details</h3>
        <div class="flex items-center space-x-2">
          <div class="w-[92%]">
            <ComboBox
              :options="patients"
              @search="searchPatient"
              @selected="selectPatient"
              :defaultOption="selectedPatient"
              placeholder="Select patient"
              :disabled="isCompleteOrPaid"
            />
          </div>
          <Button
            :disabled="isCompleteOrPaid"
            variant="primary"
            class="w-9 h-9 px-0 rounded-full"
            @click="openAddPatientModal()"
          >
            <Lucide icon="Plus" class="w-5 h-5" />
          </Button>
        </div>
        <input
          v-model="patientForm.age"
          type="number"
          placeholder="Age"
          name="age"
          id="age"
          class="block w-full mt-2 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 disabled:bg-blue-50 focus:border-indigo-500 sm:text-sm/6"
          disabled
        />
        <input
          v-model="patientForm.address"
          type="text"
          placeholder="Address"
          name="address"
          id="address"
          class="block w-full mt-2 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 disabled:bg-blue-50 focus:border-indigo-500 sm:text-sm/6"
          disabled
        />
        <input
          v-model="patientForm.city"
          type="text"
          placeholder="City"
          name="city"
          id="city"
          class="block w-full mt-2 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 disabled:bg-blue-50 focus:border-indigo-500 sm:text-sm/6"
          disabled
        />
        <input
          v-model="patientForm.phone"
          type="text"
          placeholder="Phone"
          name="phone"
          id="phone"
          class="block w-full mt-2 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 disabled:bg-blue-50 focus:border-indigo-500 sm:text-sm/6"
          disabled
        />
        <input
          v-model="patientForm.company"
          type="text"
          placeholder="Company"
          name="refills"
          id="refills"
          class="block w-full mt-2 h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 disabled:bg-blue-50 focus:border-indigo-500 sm:text-sm/6"
          disabled
        />
      </div>
      <div class="col-span-4 p-5 bg-white border rounded-lg shadow-md">
        <h3 class="font-medium pb-3">Other Prescription Information</h3>
        <div class="flex items-center space-x-2">
          <div class="w-[92%]">
            <ComboBox
              :options="prescribers"
              @search="searchPrescriber"
              @selected="selectPrescriber"
              :defaultOption="selectedPrescriber"
              placeholder="Select prescriber"
              :disabled="isCompleteOrPaid"
            />
          </div>
          <Button
            :disabled="isCompleteOrPaid"
            variant="primary"
            class="w-9 h-9 px-0 rounded-full"
            @click="openPrescriberModal()"
          >
            <Lucide icon="Plus" class="w-5 h-5" />
          </Button>
        </div>
        <div>
          <label for="dob" class="block mt-2 text-sm/6 text-gray-600">Prescription Date</label>
          <Litepicker
            v-model="prescriptionForm.prescriptionDate"
            :options="dateOptions"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            :disabled="isCompleteOrPaid"
          />
        </div>
        <div>
          <label for="dob" class="block mt-2 text-sm/6 text-gray-600"
            >Prescription Expiry Date</label
          >
          <Litepicker
            v-model="prescriptionForm.prescriptionExpDate"
            :options="dateOptions"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            :disabled="isCompleteOrPaid"
          />
        </div>
      </div>
      <div class="col-span-4 p-5 bg-white border rounded-lg shadow-md">
        <h3 class="font-medium pb-3">Totals</h3>
        <div class="flex items-center space-x-2">
          <div class="w-[50%]">
            <label for="discount-percentage" class="blocktext-sm/6 text-gray-600"
              >Discount (%)</label
            >
            <input
              v-model="prescriptionForm.discountPercentage"
              type="number"
              placeholder="Ex. 0"
              name="discount-percentage"
              id="discount-percentage"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isCompleteOrPaid"
            />
          </div>
          <div class="w-[50%]">
            <label for="discount-amount" class="blocktext-sm/6 text-gray-600"
              >Discount Amount ({{ currency }})</label
            >
            <input
              v-model="prescriptionForm.discountFlat"
              type="number"
              placeholder="Ex. 0"
              name="discount-amount"
              id="discount-amount"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isCompleteOrPaid"
            />
          </div>
        </div>
        <div class="flex items-center mt-2 space-x-2">
          <div class="w-[50%]">
            <label for="discount-percentage" class="blocktext-sm/6 text-gray-600"
              >Insurance (%)</label
            >
            <input
              v-model="prescriptionForm.insurancePercentage"
              type="number"
              placeholder="Ex. 0"
              name="discount-percentage"
              id="discount-percentage"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isCompleteOrPaid"
            />
          </div>
          <div class="w-[50%]">
            <label for="discount-amount" class="blocktext-sm/6 text-gray-600"
              >Insurance Amount ({{ currency }})</label
            >
            <input
              v-model="prescriptionForm.insuranceFlat"
              type="number"
              placeholder="Ex. 0"
              name="discount-amount"
              id="discount-amount"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              :disabled="isCompleteOrPaid"
            />
          </div>
        </div>
        <div class="flex mt-5 justify-between">
          <label for="sub-total" class="blocktext-sm/6 font-medium text-slate-900 text-lg"
            >Total:</label
          >
          <span class="font-medium text-slate-900 text-lg"
            >{{ currency }}{{ prescriptionTotal }}</span
          >
        </div>
        <div class="flex justify-between">
          <label for="insurance" class="blocktext-sm/6 text-gray-600 text-lg">Insurance:</label>
          <span class="text-lg">{{ currency }}{{ prescriptionForm.insuranceFlat }}</span>
        </div>
        <div class="flex justify-between">
          <label for="patient" class="blocktext-sm/6 text-gray-600 text-lg">Patient:</label>
          <span class="text-lg">{{ currency }}{{ patientTotal }}</span>
        </div>
        <div class="flex justify-between">
          <label for="net-due" class="blocktext-sm/6 font-medium text-slate-900 text-lg"
            >Net Due:</label
          >
          <span class="font-medium text-slate-900 text-lg">{{ currency }}{{ netDue }}</span>
        </div>
      </div>
    </div>
  </div>
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
  <AddUpdatePatientModal ref="patientModal" @submit="createPatient" />
  <AddUpdatePrescriberModal ref="prescriberModal" @submit="createPrescriber" />
</template>

<script setup lang="ts">
import Lucide from '@/components/Base/Lucide';
import { computed, onMounted, provide, Ref, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Button from '@/components/Base/Button';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useProductStore from '@/stores/product';
import useTenantStore from '@/stores/tenant';
import { FEE_CODE, LABEL, LASTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import ComboBox from '@/components/ComboBox.vue';
import { calculateAge, formatPrice, getSaleStatus, getValueByKey } from '@/utils/helper';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import { Product } from '@/composables/models/Product';
import { defaultItemsPerPage, defaultOffset } from '@/constants/pagination';
import dayjs from 'dayjs';
import _ from 'lodash';
import { Option } from '@/composables/models/Option';
import { toRaw } from 'vue';
import useLabelCodeStore from '@/stores/labelCode';
import useFeeStore from '@/stores/fee';
import FeeTag from '@/enums/feeTag';
import { CURRENCY } from '@/constants/settings';
import { PrescriptionRequest } from '@/composables/models/PrescriptionRequest';
import { PrescriptionItemRequest } from '@/composables/models/PrescriptionItemRequest';
import { Prescriber } from '@/composables/models/Prescriber';
import { Patient } from '@/composables/models/Patient';
import usePatientStore from '@/stores/patient';
import usePrescriberStore from '@/stores/prescriber';
import { PatientRequest } from '@/composables/models/PatientRequest';
import AddUpdatePatientModal from '@/components/Modals/AddUpdatePatientModal.vue';
import { Fee } from '@/composables/models/Fee';
import { PrescriptionItem } from '@/composables/models/PrescriptionItem';
import AddUpdatePrescriberModal from '@/components/Modals/AddUpdatePrescriberModal.vue';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';
import usePrescriptionStore from '@/stores/prescription';
import PrescriptionStatus from '@/enums/prescriptionStatus';
import { Menu } from '@/components/Base/Headless';
import Tippy from '@/components/Base/Tippy';
import usePrintingStore from '@/stores/printing';
import { EntityType } from '@/enums/entityType';

const router = useRouter();
const route = useRoute();

const patientModal = ref<InstanceType<typeof AddUpdatePatientModal> | null>(null);
const prescriberModal = ref<InstanceType<typeof AddUpdatePrescriberModal> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});
const selectedLabels = ref([]);
const productQoh = ref(0);
const selectedProduct = ref<Option>({
  id: 0,
  name: '',
});
const selectedPatient = ref({
  id: 0,
  name: '',
  dob: '',
  address: '',
  city: '',
  phone: '',
  company: '',
});
const selectedPrescriber = ref<Option>({
  id: 0,
  name: '',
});
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

const prescriptionForm = ref<PrescriptionRequest>({
  patientId: 0,
  prescriberId: 0,
  total: 0,
  taxes: 0,
  netDue: 0,
  status: 0,
  prescriptionDate: dayjs().format(dateFormat),
  prescriptionExpDate: dayjs().format(dateFormat),
  discountPercentage: 0,
  discountFlat: 0,
  insurancePercentage: 0,
  insuranceFlat: 0,
  prescriptionItems: [],
});
const prescriptionItemsData = ref<PrescriptionItemRequest[]>([]);
const prescriptionItemForm = ref<PrescriptionItemRequest>({
  productId: 0,
  productName: '',
  quantity: 1,
  courseDays: 0,
  refillAllowedTimes: 0,
  tax: 0,
  fee: 0,
  cost: 0,
  total: 0,
  labelCodes: [],
});

const patientForm = ref({
  age: 0,
  address: '',
  city: '',
  phone: '',
  company: '',
});

// STORE
const tenantStore = useTenantStore();
const toastStore = useToastStore();
const productStore = useProductStore();
const labelCodeStore = useLabelCodeStore();
const feeStore = useFeeStore();
const patientStore = usePatientStore();
const prescriberStore = usePrescriberStore();
const prescriptionStore = usePrescriptionStore();
const printingStore = usePrintingStore();

// COMPUTED
const currency = computed(() => {
  const data = localStorage.getItem('settings');
  const settings = data ? JSON.parse(data) : [];
  return getValueByKey(settings, CURRENCY) ?? '$';
});
const fees = computed(() => feeStore.getFees);
const patients = computed(() =>
  patientStore.getPatients.map((patient) => {
    return {
      ...patient,
      name: `${patient.lastName} ${patient.firstName}`,
    };
  }),
);
const prescribers = computed(() =>
  prescriberStore.getPrescribers.map((prescriber) => {
    return {
      ...prescriber,
      name: `${prescriber.lastName} ${prescriber.firstName}`,
    };
  }),
);
const products = computed(() =>
  productStore.getProducts.map((product) => {
    return {
      ...product,
      name: `${product.label} (${product.qoh}) | ${currency.value}${product.retailCost}`,
    };
  }),
);
const labelCodes = computed(() =>
  labelCodeStore.getLabelCodes.map((labelCode) => {
    return {
      id: labelCode.id,
      name: labelCode.labelCode,
      details: labelCode.label,
    };
  }),
);
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
const prescriptionId = computed(() =>
  route.params.id ? parseInt(route.params.id.toString()) : null,
);
const prescription = computed(() =>
  prescriptionId.value ? prescriptionStore.getPrescription : null,
);
const prescriptionStatus = computed(() =>
  prescription.value ? getSaleStatus(prescription.value?.status) : '',
);
const prescriptionItemSubTotal = computed(() => {
  const subTotal =
    (prescriptionItemForm.value.cost + prescriptionItemForm.value.tax) *
      prescriptionItemForm.value.quantity +
    prescriptionItemForm.value.fee;
  return parseFloat(subTotal.toFixed(2));
});
const prescriptionTaxes = computed(() => {
  const amount = prescriptionItemsData.value.reduce(
    (sum, item) => sum + item.tax * item.quantity + item.fee,
    0,
  );
  return parseFloat(amount.toFixed(2));
});
const prescriptionTotal = computed(() => {
  const amount = prescriptionItemsData.value.reduce((sum, item) => sum + item.total, 0);
  return parseFloat(amount.toFixed(2));
});
const patientTotal = computed(() => prescriptionTotal.value - prescriptionForm.value.insuranceFlat);
const netDue = computed(() => patientTotal.value - prescriptionForm.value.discountFlat);

const isCompleted = computed(() => prescription.value?.status === PrescriptionStatus.COMPLETED);
const isInProgress = computed(() => prescription.value?.status === PrescriptionStatus.IN_PROGRESS);
const isPaid = computed(() => prescription.value?.status === PrescriptionStatus.PAID);
const isCompleteOrPaid = computed(() => isCompleted.value || isPaid.value);
const canSavePrescription = computed(
  () =>
    prescriptionItemsData.value.length > 0 &&
    prescriptionForm.value.patientId > 0 &&
    prescriptionForm.value.prescriberId,
);
const canComplete = computed(
  () =>
    prescription.value?.status !== PrescriptionStatus.COMPLETED &&
    prescriptionItemsData.value.length > 0 &&
    prescriptionForm.value.patientId > 0 &&
    prescriptionForm.value.prescriberId,
);

// METHOD
const tenantVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const closeAddEditPrescriptionForm = () => {
  router.push({
    name: 'prescription-list',
    params: { identifier: route.params.identifier },
  });
};

const getFeesData = async (searchString: string = '') => {
  await feeStore.getFeesApi({
    search: searchString,
    sortBy: FEE_CODE,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
    tag: FeeTag.FEE,
  });
};

const getProductsData = async (searchString: string = '') => {
  await productStore.getProductsApi({
    search: searchString,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const searchProduct = async (searchFilter: string) => {
  await getProductsData(searchFilter);
};
const searchFee = async (searchFilter: string) => {
  await getFeesData(searchFilter);
};
const searchPatient = async (searchFilter: string) => {
  await getPatientsData(searchFilter);
};
const searchPrescriber = async (searchFilter: string) => {
  await getPrescribersData(searchFilter);
};

const selectProduct = (product: Product) => {
  prescriptionItemForm.value.productId = product.id;
  prescriptionItemForm.value.productName = product.label;
  productQoh.value = product.qoh;
  prescriptionItemForm.value.tax = product.fee ? product.fee.value : 0;
  prescriptionItemForm.value.cost = product.retailCost;
};

const selectFee = (fee: Fee) => {
  prescriptionItemForm.value.fee = fee.cost;
};

const selectPatient = (patient: any) => {
  prescriptionForm.value.patientId = patient.id;
  patientForm.value.age = patient.dob ? calculateAge(patient.dob) : 0;
  patientForm.value.address = patient.address ?? '';
  patientForm.value.city = patient.city;
  patientForm.value.company = patient.company;
  patientForm.value.phone = patient.phone;
};

const selectPrescriber = (prescriber: Prescriber) => {
  prescriptionForm.value.prescriberId = prescriber.id;
};

const resetFormData = () => {
  prescriptionItemForm.value.productId = 0;
  prescriptionItemForm.value.quantity = 1;
  prescriptionItemForm.value.courseDays = 0;
  prescriptionItemForm.value.refillAllowedTimes = 0;
  prescriptionItemForm.value.tax = 0;
  prescriptionItemForm.value.fee = 0;
  prescriptionItemForm.value.cost = 0;
  selectedProduct.value = { id: 0, name: '' };
};

const addProduct = () => {
  const rawFormData = toRaw(prescriptionItemForm.value);
  const existingItem = prescriptionItemsData.value.find(
    (item) => item.productId === rawFormData.productId,
  );
  if (existingItem) {
    existingItem.quantity += rawFormData.quantity;
  } else {
    prescriptionItemsData.value.push({
      productId: rawFormData.productId,
      productName: rawFormData.productName,
      quantity: rawFormData.quantity,
      courseDays: rawFormData.courseDays,
      refillAllowedTimes: rawFormData.refillAllowedTimes,
      tax: rawFormData.tax,
      fee: rawFormData.fee,
      cost: rawFormData.cost,
      total: prescriptionItemSubTotal.value,
      labelCodes: rawFormData.labelCodes,
    });
  }
  resetFormData();
};

const removeItem = (index: number) => {
  prescriptionItemsData.value.splice(index, 1);
};

const getLabelCodesData = async (queryString: string = '') => {
  await labelCodeStore.getLabelCodesApi({
    search: queryString,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const getPatientsData = async (queryString: string = '') => {
  await patientStore.getPatientsApi({
    search: queryString,
    sortBy: LASTNAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const getPrescribersData = async (queryString: string = '') => {
  await prescriberStore.getPrescribersApi({
    search: queryString,
    sortBy: LASTNAME,
    sortOrder: SortOrder.ASC,
    offset: defaultOffset,
    limit: defaultItemsPerPage,
  });
};

const selectLabels = async (value: Option[]) => {
  prescriptionItemForm.value.labelCodes = value.map((option) => {
    return { id: option.id, code: option.name };
  });
};

const openAddPatientModal = (patient: Patient | null = null) => {
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

const openPrescriberModal = (prescriber: Prescriber | null = null) => {
  prescriberModal.value?.openPrescriberModal(
    {
      firstName: prescriber ? prescriber.firstName : '',
      lastName: prescriber ? prescriber.lastName : '',
      phone: prescriber ? prescriber.phone : '',
      registrationNumber: prescriber ? prescriber.registrationNumber : '',
      address: prescriber ? prescriber.address : '',
    },
    prescriber ? prescriber.id : null,
  );
};

const createPatient = async (formData: Ref<PatientRequest>) => {
  await patientStore.createPatientApi(formData.value);
  basicNonStickyNotificationToggle();
  await getPatientsData();
};

const createPrescriber = async (formData: Ref<PrescriberRequest>) => {
  await prescriberStore.createPrescriberApi(formData.value);
  basicNonStickyNotificationToggle();
  await getPrescribersData();
};

const savePrescription = async (status: number) => {
  prescriptionForm.value.status = status;
  prescriptionForm.value.total = prescriptionTotal.value;
  prescriptionForm.value.netDue = netDue.value;
  prescriptionForm.value.taxes = prescriptionTaxes.value;
  prescriptionForm.value.prescriptionItems = prescriptionItemsData.value;
  if (prescriptionId.value) {
    await prescriptionStore.updatePrescriptionApi(prescriptionId.value, prescriptionForm.value);
    await getPrescriptionData();
  } else {
    await prescriptionStore.createPrescriptionApi(prescriptionForm.value);
  }
  basicNonStickyNotificationToggle();
};

const setPatientForm = () => {
  if (prescription.value) {
    selectedPatient.value = {
      id: prescription.value.patient.id,
      name: `${prescription.value.patient.lastName} ${prescription.value.patient.firstName}`,
      dob: prescription.value.patient.dob,
      address: prescription.value.patient.address,
      city: prescription.value.patient.city,
      phone: prescription.value.patient.phone,
      company: prescription.value.patient.company,
    };
  }
};
const setPrescriberAndOtherForm = () => {
  if (prescription.value) {
    selectedPrescriber.value = {
      id: prescription.value.prescriber.id,
      name: `${prescription.value.prescriber.lastName} ${prescription.value.prescriber.firstName}`,
    };
    prescriptionForm.value.prescriptionDate = prescription.value.prescriptionDate;
    prescriptionForm.value.prescriptionExpDate = prescription.value.prescriptionExpDate;
  }
};
const setCosts = () => {
  if (prescription.value) {
    prescriptionForm.value.discountPercentage = prescription.value.discountPercentage;
    prescriptionForm.value.discountFlat = prescription.value.discountFlat;
    prescriptionForm.value.insurancePercentage = prescription.value.insurancePercentage;
    prescriptionForm.value.insuranceFlat = prescription.value.insuranceFlat;
  }
};

const setPrescriptionForm = () => {
  if (prescription.value) {
    prescriptionItemsData.value = prescription.value.prescriptionItems.map((prescriptionItem) => {
      return {
        productId: prescriptionItem.product.id,
        productName: prescriptionItem.product.name,
        quantity: prescriptionItem.quantity,
        courseDays: prescriptionItem.courseDays,
        refillAllowedTimes: prescriptionItem.refillAllowedTimes,
        tax: prescriptionItem.tax,
        fee: prescriptionItem.fee,
        cost: prescriptionItem.cost,
        total: prescriptionItem.total,
        labelCodes: prescriptionItem.labelCodes.map((lCode) => {
          return { id: lCode.id, code: lCode.labelCode };
        }),
      };
    });
    setPatientForm();
    setPrescriberAndOtherForm();
    setCosts();
  }
};

const getPrescriptionData = async () => {
  if (prescriptionId.value) {
    await prescriptionStore.getPrescriptionApi(prescriptionId.value);
    setPrescriptionForm();
  }
};

const printInvoice = () => {
  if (prescriptionId.value) {
    printingStore.printInvoiceApi(prescriptionId.value, EntityType.PRESCRIPTION);
  }
};
const printProforma = () => {
  if (prescriptionId.value) {
    printingStore.printProformaApi(prescriptionId.value, EntityType.PRESCRIPTION);
  }
};

const printLabels = () => {
  if (prescriptionId.value) {
    printingStore.printLabelsApi(prescriptionId.value);
  }
};

onMounted(async () => {
  await tenantVerification();
  await getPrescriptionData();
  await Promise.all([
    getProductsData(),
    getFeesData(),
    getLabelCodesData(),
    getPatientsData(),
    getPrescribersData(),
  ]);
});
</script>
