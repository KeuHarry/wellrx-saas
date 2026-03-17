<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">Add User</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-cols-1 gap-4 p-10">
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="first-name" class="block text-sm/6 text-gray-900">First Name</label>
            <input
              v-model="formData.firstName"
              type="text"
              placeholder="Enter first name"
              name="first-name"
              id="first-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="last-name" class="block text-sm/6 text-gray-900">Last Name</label>
            <input
              v-model="formData.lastName"
              type="text"
              placeholder="Enter last name"
              name="last-name"
              id="last-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-7 gap-2">
          <div class="col-span-3">
            <label for="username" class="block text-sm/6 text-gray-900">Username</label>
            <input
              v-model="formData.username"
              type="text"
              placeholder="Enter username"
              name="username"
              id="username"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="email" class="block text-sm/6 text-gray-900">Email</label>
            <input
              v-model="formData.email"
              type="email"
              placeholder="Ex. user@example.com"
              name="email"
              id="email"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-5 gap-2">
          <div class="col-span-2">
            <label for="username" class="block text-sm/6 text-gray-900">Role</label>
            <select
              v-model="formData.roleId"
              name="tag"
              id="tag"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm"
            >
              <option :value="role.id" v-for="(role, rkey) in roles" :key="rkey">
                {{ role.label }}
              </option>
            </select>
          </div>
          <div class="col-span-2">
            <label for="language" class="block text-sm/6 text-gray-900">Language</label>
            <select
              v-model="formData.preferredLang"
              name="tag"
              id="tag"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm"
            >
              <option :value="language.value" v-for="(language, lkey) in languages" :key="lkey">
                {{ language.name }}
              </option>
            </select>
          </div>
          <div class="col-span-1 content-center justify-items-center">
            <div class="flex items-center space-x-2 mt-6">
              <input
                v-model="formData.isActive"
                type="checkbox"
                name="active"
                id="active"
                class="block w-4 h-4 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              />
              <label for="active" class="block text-sm/6 text-gray-900">Active</label>
            </div>
          </div>
        </div>
      </div>
      <Dialog.Footer>
        <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20"> Save </Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, onMounted, ref } from 'vue';
import { UserRequest } from '@/composables/models/UserRequest';
import Language from '@/enums/language';
import { languageList } from '@/constants/languageList';
import useRoleStore from '@/stores/role';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';

const emit = defineEmits(['submit']);

const show = ref(false);
const userId = ref<number | null>(null);
const formData = ref<UserRequest>({
  firstName: '',
  lastName: '',
  username: '',
  email: '',
  isActive: true,
  preferredLang: Language.English.toString(),
  roleId: null,
});
const languages = ref(languageList);
const route = useRoute();

const tenantStore = useTenantStore();
const roleStore = useRoleStore();

// COMPUTED
const roles = computed(() => roleStore.getRoles);

// METHODS
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const openUserModal = (userRequest: UserRequest, id: number | null) => {
  show.value = true;
  userId.value = id;
  formData.value = userRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  userId.value = null;
  formData.value.firstName = '';
  formData.value.lastName = '';
  formData.value.username = '';
  formData.value.email = '';
  formData.value.isActive = true;
  formData.value.preferredLang = Language.English.toString();
  formData.value.roleId = null;
};

const handleSubmit = async () => {
  emit('submit', formData, userId.value);
  closeModal();
};

const getRolesData = async () => {
  await roleStore.getRolesApi();
};

defineExpose({
  openUserModal,
});

onMounted(async () => {
  await accountVerification();
  await getRolesData();
});
</script>
