<template>
  <div class="flex items-center mt-8 intro-y">
    <h2 class="mr-auto text-lg font-medium">Settings</h2>
  </div>
  <div class="grid grid-cols-12 gap-3 mt-5">
    <!-- BEGIN: Setting Menu -->
    <div class="flex flex-col-reverse col-span-12 lg:col-span-4 2xl:col-span-3 lg:block">
      <div class="mt-5 intro-y box lg:mt-0">
        <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
          <a
            v-for="item in menuItems"
            :key="item.id"
            class="flex items-center cursor-pointer"
            :class="{
              'font-medium text-primary': activeSection === item.id,
              'mt-5': item.id !== GENERAL,
            }"
            @click="setActiveSection(item.id)"
          >
            <Lucide :icon="item.icon" class="w-4 h-4 mr-2" />{{ item.label }}
          </a>
        </div>
      </div>
    </div>
    <!-- END: Setting Menu -->
    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
      <div class="grid grid-cols-12 gap-6">
        <div v-if="activeSection === GENERAL" class="col-span-12 intro-y box 2xl:col-span-12">
          <div
            class="flex items-center px-5 py-5 border-b sm:py-3 border-slate-200/60 dark:border-darkmode-400"
          >
            <h2 class="mr-auto text-base font-medium text-slate-700">General Info</h2>
          </div>
          <div class="grid grid-cols-9 gap-2 p-5">
            <div class="col-span-4">
              <label for="pharmacy-name" class="block text-sm/6 text-gray-900">
                Pharmacy Name
              </label>
              <input
                v-model="formData.pharmacyName"
                type="text"
                placeholder="Enter pharmacy name"
                name="pharmacy-name"
                id="pharmacy-name"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="address" class="block text-sm/6 text-gray-900">Address</label>
              <input
                v-model="formData.pharmacyAddress"
                type="text"
                placeholder="Enter pharmacy address"
                name="address"
                id="address"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-2">
              <label for="city" class="block text-sm/6 text-gray-900">City</label>
              <input
                v-model="formData.pharmacyCity"
                type="text"
                placeholder="Ex. Tema"
                name="city"
                id="city"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>
          <div class="grid grid-cols-9 gap-2 px-5 mb-5">
            <div class="col-span-3">
              <label for="phone" class="block text-sm/6 text-gray-900"> Phone Number </label>
              <input
                v-model="formData.pharmacyPhone"
                type="text"
                placeholder="Enter pharmacy phone number"
                name="phone"
                id="phone"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="email" class="block text-sm/6 text-gray-900">Email</label>
              <input
                v-model="formData.pharmacyEmail"
                type="email"
                placeholder="pharma@example.com"
                name="email"
                id="email"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="proprio" class="block text-sm/6 text-gray-900">Proprietor</label>
              <input
                v-model="formData.proprietor"
                type="text"
                placeholder="Ex. Dr. John Doe"
                name="proprio"
                id="proprio"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>
          <div class="px-5 mb-5">
            <label for="footer" class="block text-sm/6 text-gray-900">Footer Message</label>
            <input
              v-model="formData.footerMessage"
              type="text"
              placeholder="Ex. Your wellness..."
              name="footer"
              id="footer"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div
            class="flex items-center px-5 py-5 border-b sm:py-3 border-slate-200/60 dark:border-darkmode-400"
          >
            <h2 class="mr-auto text-base font-medium text-slate-700">Currency</h2>
          </div>
          <div class="grid grid-cols-12 gap-2 p-5 mb-5">
            <div class="col-span-3">
              <label for="currency" class="block text-sm/6 text-gray-900"> Currency </label>
              <input
                v-model="formData.currency"
                type="text"
                placeholder="Ex. $"
                name="currency"
                id="curency"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="place" class="block text-sm/6 text-gray-900"
                >Number Of Decimal Places</label
              >
              <input
                v-model="formData.decimalDigit"
                type="text"
                placeholder="Ex. 2"
                name="place"
                id="place"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="decimal-separator" class="block text-sm/6 text-gray-900"
                >Decimal Separator</label
              >
              <input
                v-model="formData.decimalSeparator"
                type="text"
                placeholder="Ex. ."
                name="decimal-separator"
                id="decimal-separator"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
            <div class="col-span-3">
              <label for="thousand-separator" class="block text-sm/6 text-gray-900"
                >Per Thousand Separator</label
              >
              <input
                v-model="formData.thousandSeparator"
                type="text"
                placeholder="Ex. ,"
                name="thousand-separator"
                id="thousand-separator"
                class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-700 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>
          <div class="flex px-5 pb-5 justify-end">
            <Button @click="saveSettings" variant="primary" type="button" class="w-20">Save</Button>
          </div>
        </div>
        <div v-if="activeSection === SUBSCRIPTION" class="col-span-12 intro-y box 2xl:col-span-12">
          <div
            class="flex items-center px-5 py-5 border-b sm:py-3 border-slate-200/60 dark:border-darkmode-400"
          >
            <h2 class="mr-auto text-base font-medium text-black">Subscription</h2>
          </div>
          <div class="grid grid-cols-9 gap-2 p-5"></div>
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
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Lucide from '@/components/Base/Lucide';
import { MenuItem } from '@/types/menu-setting';
import { GENERAL, SUBSCRIPTION } from '@/constants/menuItemSetting';
import Button from '@/components/Base/Button';
import { Setting } from '@/composables/models/Setting';
import { getValueByKey } from '@/utils/helper';
import {
  CURRENCY,
  DECIMAL_DIGIT,
  DECIMAL_SEPARATOR,
  FOOTER_MESSAGE,
  PHARMA_ADDRESS,
  PHARMA_CITY,
  PHARMA_EMAIL,
  PHARMA_NAME,
  PHARMA_PHONE,
  PROPRIETOR,
  THOUSAND_SEPARATOR,
} from '@/constants/settings';
import useSettingStore from '@/stores/setting';
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import { type NotificationElement } from '@/components/Base/Notification';
import Notification from '@/components/Base/Notification';
import NotificationType from '@/enums/notification';
import useToastStore from '@/stores/toast';

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const activeSection = ref('general');

const menuItems = ref<MenuItem[]>([
  { id: 'general', label: 'General Settings', icon: 'Settings' },
  { id: 'subscription', label: 'Subscription', icon: 'File' },
  { id: 'insurance', label: 'Insurance', icon: 'Shield' },
  { id: 'printing', label: 'Printing', icon: 'Printer' },
  { id: 'backup', label: 'Backup', icon: 'Save' },
]);

const formData = ref({
  pharmacyName: '',
  pharmacyAddress: '',
  pharmacyCity: '',
  pharmacyPhone: '',
  pharmacyEmail: '',
  proprietor: '',
  footerMessage: '',
  currency: '',
  decimalDigit: '',
  decimalSeparator: '',
  thousandSeparator: '',
});

const settings = ref<Setting[]>([]);
const route = useRoute();

// STORE
const tenantStore = useTenantStore();
const settingStore = useSettingStore();
const toastStore = useToastStore();

// COMPUTED
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

// METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const setActiveSection = (sectionId: string) => {
  activeSection.value = sectionId;
};

const setSettingsData = async () => {
  const data = localStorage.getItem('settings');
  settings.value = data ? JSON.parse(data) : [];
  await setFormData();
};

const setFormData = async () => {
  formData.value.pharmacyName = getValueByKey(settings.value, PHARMA_NAME) ?? '';
  formData.value.pharmacyAddress = getValueByKey(settings.value, PHARMA_ADDRESS) ?? '';
  formData.value.pharmacyCity = getValueByKey(settings.value, PHARMA_CITY) ?? '';
  formData.value.pharmacyPhone = getValueByKey(settings.value, PHARMA_PHONE) ?? '';
  formData.value.pharmacyEmail = getValueByKey(settings.value, PHARMA_EMAIL) ?? '';
  formData.value.proprietor = getValueByKey(settings.value, PROPRIETOR) ?? '';
  formData.value.footerMessage = getValueByKey(settings.value, FOOTER_MESSAGE) ?? '';
  formData.value.currency = getValueByKey(settings.value, CURRENCY) ?? '';
  formData.value.decimalDigit = getValueByKey(settings.value, DECIMAL_DIGIT) ?? '';
  formData.value.decimalSeparator = getValueByKey(settings.value, DECIMAL_SEPARATOR) ?? '';
  formData.value.thousandSeparator = getValueByKey(settings.value, THOUSAND_SEPARATOR) ?? '';
};

const updateSettingsData = async () => {
  settings.value = settings.value.map((setting) => {
    switch (setting.key) {
      case PHARMA_NAME:
        setting.value = formData.value.pharmacyName;
        break;
      case PHARMA_ADDRESS:
        setting.value = formData.value.pharmacyAddress;
        break;
      case PHARMA_CITY:
        setting.value = formData.value.pharmacyCity;
        break;
      case PHARMA_PHONE:
        setting.value = formData.value.pharmacyPhone;
        break;
      case PHARMA_EMAIL:
        setting.value = formData.value.pharmacyEmail;
        break;
      case PROPRIETOR:
        setting.value = formData.value.proprietor;
        break;
      case FOOTER_MESSAGE:
        setting.value = formData.value.footerMessage;
        break;
      case CURRENCY:
        setting.value = formData.value.currency;
        break;
      case DECIMAL_DIGIT:
        setting.value = formData.value.decimalDigit;
        break;
      case DECIMAL_SEPARATOR:
        setting.value = formData.value.decimalSeparator;
        break;
      case THOUSAND_SEPARATOR:
        setting.value = formData.value.thousandSeparator;
        break;

      default:
        break;
    }
    return setting;
  });
};

const saveSettings = async () => {
  await updateSettingsData();
  await settingStore.updateSettingApi({ settings: settings.value });
  basicNonStickyNotificationToggle();
};

onMounted(async () => {
  await accountVerification();
  await setSettingsData();
});
</script>
