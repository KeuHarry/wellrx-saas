<template>
  <!-- BEGIN: Super Large Slide Over Content -->
  <Slideover size="xl" :open="superlargeSlideoverSizePreview">
    <Slideover.Panel>
      <a
        @click="
          (event: MouseEvent) => {
            event.preventDefault();
            setSuperlargeSlideoverSizePreview(false);
          }
        "
        class="absolute top-0 left-0 right-auto mt-4 -ml-12"
        href="#"
      >
        <Lucide icon="X" class="w-8 h-8 text-white" />
      </a>
      <Slideover.Title class="px-5 py-3">
        <h2 class="mr-auto text-base font-medium">Assign permissions</h2>
        <Button variant="primary" class="hidden sm:flex" @click="savePermissions">
          <Lucide icon="SaveAll" class="w-4 h-4 mr-2" />
          Save
        </Button>
      </Slideover.Title>
      <Slideover.Description>
        Role - <span class="text-base font-medium">{{ roleData.label }}</span>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isAdminAllSelected"
              @change="toggleSelectAllAdmin"
              type="checkbox"
              name="all-admin-perm"
              id="all-admin-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Administration</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, pkey) in adminPermissions"
            :key="pkey"
          >
            <input
              v-model="selectedAdminPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isProductAllSelected"
              @change="toggleSelectAllProduct"
              type="checkbox"
              name="all-product-perm"
              id="all-product-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Products</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, pkey) in productPermissions"
            :key="pkey"
          >
            <input
              v-model="selectedProductPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isStockAllSelected"
              @change="toggleSelectAllStock"
              type="checkbox"
              name="all-stock-perm"
              id="all-stock-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Stock Management</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, skey) in stockPermissions"
            :key="skey"
          >
            <input
              v-model="selectedStockPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isPosAllSelected"
              @change="toggleSelectAllPos"
              type="checkbox"
              name="all-pos-perm"
              id="all-pos-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Point of Sale</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, pkey) in posPermissions"
            :key="pkey"
          >
            <input
              v-model="selectedPosPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isDocAllSelected"
              @change="toggleSelectAllDoc"
              type="checkbox"
              name="all-doc-perm"
              id="all-doc-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Documents</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, dkey) in documentPermissions"
            :key="dkey"
          >
            <input
              v-model="selectedDocumentPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isPrescAllSelected"
              @change="toggleSelectAllPresc"
              type="checkbox"
              name="all-presc-perm"
              id="all-presc-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Prescriptions</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, pkey) in prescriptionPermissions"
            :key="pkey"
          >
            <input
              v-model="selectedPrescriptionPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isInsurerAllSelected"
              @change="toggleSelectAllInsurer"
              type="checkbox"
              name="all-insurer-perm"
              id="all-insurer-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Insurance</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, ikey) in insurerPermissions"
            :key="ikey"
          >
            <input
              v-model="selectedInsurerPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-5 p-2 border border-b-zinc-400">
          <div class="flex items-center space-x-2">
            <input
              :checked="isReportAllSelected"
              @change="toggleSelectAllReport"
              type="checkbox"
              name="all-report-perm"
              id="all-report-perm"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <h3 class="text-black">Reports</h3>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
          <div
            class="flex items-center space-x-2"
            v-for="(permission, rkey) in reportPermissions"
            :key="rkey"
          >
            <input
              v-model="selectedReportPermissions"
              :value="permission.id"
              type="checkbox"
              :name="'permission-' + permission.id"
              :id="'permission-' + permission.id"
              class="block w-4 h-4 border cursor-pointer border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
            <label :for="'permission-' + permission.id" class="block text-xs text-gray-900">{{
              permission.label
            }}</label>
          </div>
        </div>
      </Slideover.Description>
    </Slideover.Panel>
  </Slideover>
  <!-- END: Super Large Slide Over Content -->
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Lucide from '@/components/Base/Lucide';
import { Slideover } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import { Role } from '@/composables/models/Role';
import { computed } from 'vue';
import useRoleStore from '@/stores/role';
import { onMounted } from 'vue';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';

const route = useRoute();
const superlargeSlideoverSizePreview = ref(false);
const setSuperlargeSlideoverSizePreview = (value: boolean) => {
  superlargeSlideoverSizePreview.value = value;
};
const roleData = ref<Role>({ id: 0, label: '', description: '', permissionsCount: 0 });
const selectedPermissions = ref<number[]>([]);
const selectedAdminPermissions = ref<number[]>([]);
const selectedProductPermissions = ref<number[]>([]);
const selectedStockPermissions = ref<number[]>([]);
const selectedPosPermissions = ref<number[]>([]);
const selectedDocumentPermissions = ref<number[]>([]);
const selectedPrescriptionPermissions = ref<number[]>([]);
const selectedInsurerPermissions = ref<number[]>([]);
const selectedReportPermissions = ref<number[]>([]);

// STORE
const tenantStore = useTenantStore();
const roleStore = useRoleStore();

// COMPUTED
const permissions = computed(() => roleStore.getPermissions);
const rolePermissions = computed(() => roleStore.getRolePermissions);

const initRoleAdminPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        permission.label.toLowerCase().includes('role') ||
        permission.label.toLowerCase().includes('user') ||
        permission.label.toLowerCase().includes('backup') ||
        permission.label.toLowerCase().includes('log') ||
        permission.label.toLowerCase().includes('setting') ||
        permission.label.toLowerCase().includes('view admin'),
    )
    .map((permission) => permission.id),
);

const adminPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      permission.label.toLowerCase().includes('role') ||
      permission.label.toLowerCase().includes('user') ||
      permission.label.toLowerCase().includes('backup') ||
      permission.label.toLowerCase().includes('log') ||
      permission.label.toLowerCase().includes('setting') ||
      permission.label.toLowerCase().includes('view admin'),
  ),
);

const initRoleProductPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        (permission.label.toLowerCase().includes('product') &&
          !permission.label.toLowerCase().includes('report')) ||
        permission.label.toLowerCase().includes('categor') ||
        permission.label.toLowerCase().includes('shel') ||
        permission.label.toLowerCase().includes('fee') ||
        permission.label.toLowerCase().includes('tax') ||
        permission.label.toLowerCase().includes('search ndc'),
    )
    .map((permission) => permission.id),
);

const productPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      (permission.label.toLowerCase().includes('product') &&
        !permission.label.toLowerCase().includes('report')) ||
      permission.label.toLowerCase().includes('categor') ||
      permission.label.toLowerCase().includes('shel') ||
      permission.label.toLowerCase().includes('fee') ||
      permission.label.toLowerCase().includes('tax') ||
      permission.label.toLowerCase().includes('search ndc'),
  ),
);

const initRoleStockPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        permission.label.toLowerCase().includes('inventor') ||
        permission.label.toLowerCase().includes('supplier') ||
        (permission.label.toLowerCase().includes('stock') &&
          !permission.label.toLowerCase().includes('report')) ||
        permission.label.toLowerCase().includes('order'),
    )
    .map((permission) => permission.id),
);

const stockPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      permission.label.toLowerCase().includes('inventor') ||
      permission.label.toLowerCase().includes('supplier') ||
      (permission.label.toLowerCase().includes('stock') &&
        !permission.label.toLowerCase().includes('report')) ||
      permission.label.toLowerCase().includes('order'),
  ),
);

const initRolePosPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        (permission.label.toLowerCase().includes('sale') &&
          !permission.label.toLowerCase().includes('report')) ||
        permission.label.toLowerCase().includes('customer') ||
        permission.label.toLowerCase().includes('sell on') ||
        (permission.label.toLowerCase().includes('cash') &&
          !permission.label.toLowerCase().includes('report')) ||
        permission.label.toLowerCase().includes('pos modul') ||
        permission.label.toLowerCase().includes('discount'),
    )
    .map((permission) => permission.id),
);

const posPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      (permission.label.toLowerCase().includes('sale') &&
        !permission.label.toLowerCase().includes('report')) ||
      permission.label.toLowerCase().includes('customer') ||
      permission.label.toLowerCase().includes('sell on') ||
      (permission.label.toLowerCase().includes('cash') &&
        !permission.label.toLowerCase().includes('report')) ||
      permission.label.toLowerCase().includes('pos modul') ||
      permission.label.toLowerCase().includes('discount'),
  ),
);

const initRoleDocumentPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        permission.label.toLowerCase().includes('print') ||
        permission.label.toLowerCase().includes('import'),
    )
    .map((permission) => permission.id),
);

const documentPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      permission.label.toLowerCase().includes('print') ||
      permission.label.toLowerCase().includes('import'),
  ),
);

const initRoleReportPermissions = computed(() =>
  rolePermissions.value
    .filter((permission) => permission.label.toLowerCase().includes('report'))
    .map((permission) => permission.id),
);

const reportPermissions = computed(() =>
  permissions.value.filter((permission) => permission.label.toLowerCase().includes('report')),
);

const initRoleInsurerPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        permission.label.toLowerCase().includes('insurer') ||
        permission.label.toLowerCase().includes('insurance') ||
        permission.label.toLowerCase().includes('coverage'),
    )
    .map((permission) => permission.id),
);

const insurerPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      permission.label.toLowerCase().includes('insurer') ||
      permission.label.toLowerCase().includes('insurance') ||
      permission.label.toLowerCase().includes('coverage'),
  ),
);

const initRolePrescriptionPermissions = computed(() =>
  rolePermissions.value
    .filter(
      (permission) =>
        permission.label.toLowerCase().includes('prescription') ||
        permission.label.toLowerCase().includes('patient') ||
        permission.label.toLowerCase().includes('doctor') ||
        permission.label.toLowerCase().includes('intermedia') ||
        permission.label.toLowerCase().includes('label cod'),
    )
    .map((permission) => permission.id),
);

const prescriptionPermissions = computed(() =>
  permissions.value.filter(
    (permission) =>
      permission.label.toLowerCase().includes('prescription') ||
      permission.label.toLowerCase().includes('patient') ||
      permission.label.toLowerCase().includes('doctor') ||
      permission.label.toLowerCase().includes('intermedia') ||
      permission.label.toLowerCase().includes('label cod'),
  ),
);

const isAdminAllSelected = computed(
  () => selectedAdminPermissions.value.length === adminPermissions.value.length,
);
const isProductAllSelected = computed(
  () => selectedProductPermissions.value.length === productPermissions.value.length,
);
const isStockAllSelected = computed(
  () => selectedStockPermissions.value.length === stockPermissions.value.length,
);
const isPosAllSelected = computed(
  () => selectedPosPermissions.value.length === posPermissions.value.length,
);
const isDocAllSelected = computed(
  () => selectedDocumentPermissions.value.length === documentPermissions.value.length,
);
const isPrescAllSelected = computed(
  () => selectedPrescriptionPermissions.value.length === prescriptionPermissions.value.length,
);
const isInsurerAllSelected = computed(
  () => selectedInsurerPermissions.value.length === insurerPermissions.value.length,
);
const isReportAllSelected = computed(
  () => selectedReportPermissions.value.length === reportPermissions.value.length,
);

// METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const savePermissions = async () => {
  selectedPermissions.value = [
    ...selectedAdminPermissions.value,
    ...selectedProductPermissions.value,
    ...selectedStockPermissions.value,
    ...selectedPosPermissions.value,
    ...selectedDocumentPermissions.value,
    ...selectedPrescriptionPermissions.value,
    ...selectedInsurerPermissions.value,
    ...selectedReportPermissions.value,
  ];

  await roleStore.assignPermissionsApi({
    roleId: roleData.value.id,
    permissions: selectedPermissions.value,
  });
};

const openSlideover = async (role: Role) => {
  roleData.value.id = role.id;
  roleData.value.label = role.label;
  await getRoleData();
  setSuperlargeSlideoverSizePreview(true);
  selectedAdminPermissions.value = initRoleAdminPermissions.value;
  selectedProductPermissions.value = initRoleProductPermissions.value;
  selectedStockPermissions.value = initRoleStockPermissions.value;
  selectedPosPermissions.value = initRolePosPermissions.value;
  selectedDocumentPermissions.value = initRoleDocumentPermissions.value;
  selectedPrescriptionPermissions.value = initRolePrescriptionPermissions.value;
  selectedInsurerPermissions.value = initRoleInsurerPermissions.value;
  selectedReportPermissions.value = initRoleReportPermissions.value;
};

const toggleSelectAllAdmin = () => {
  if (isAdminAllSelected.value) {
    selectedAdminPermissions.value = [];
  } else {
    selectedAdminPermissions.value = adminPermissions.value.map((permission) => permission.id);
  }
};

const toggleSelectAllProduct = () => {
  if (isProductAllSelected.value) {
    selectedProductPermissions.value = [];
  } else {
    selectedProductPermissions.value = productPermissions.value.map((permission) => permission.id);
  }
};

const toggleSelectAllStock = () => {
  if (isStockAllSelected.value) {
    selectedStockPermissions.value = [];
  } else {
    selectedStockPermissions.value = stockPermissions.value.map((permission) => permission.id);
  }
};

const toggleSelectAllPos = () => {
  if (isPosAllSelected.value) {
    selectedPosPermissions.value = [];
  } else {
    selectedPosPermissions.value = posPermissions.value.map((permission) => permission.id);
  }
};

const toggleSelectAllDoc = () => {
  if (isDocAllSelected.value) {
    selectedDocumentPermissions.value = [];
  } else {
    selectedDocumentPermissions.value = documentPermissions.value.map(
      (permission) => permission.id,
    );
  }
};

const toggleSelectAllPresc = () => {
  if (isPrescAllSelected.value) {
    selectedPrescriptionPermissions.value = [];
  } else {
    selectedPrescriptionPermissions.value = prescriptionPermissions.value.map(
      (permission) => permission.id,
    );
  }
};

const toggleSelectAllInsurer = () => {
  if (isInsurerAllSelected.value) {
    selectedInsurerPermissions.value = [];
  } else {
    selectedInsurerPermissions.value = insurerPermissions.value.map((permission) => permission.id);
  }
};

const toggleSelectAllReport = () => {
  if (isReportAllSelected.value) {
    selectedReportPermissions.value = [];
  } else {
    selectedReportPermissions.value = reportPermissions.value.map((permission) => permission.id);
  }
};

const getRoleData = async () => {
  if (roleData.value.id > 0) {
    await roleStore.getRoleApi(roleData.value.id);
  }
};

const getAllPermissions = async () => {
  await roleStore.getPermissionsApi();
};

defineExpose({
  openSlideover,
});

onMounted(async () => {
  await accountVerification();
  await getAllPermissions();
});
</script>
