<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Roles</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div
      class="flex flex-wrap justify-between items-center col-span-12 mt-2 intro-y sm:flex-nowrap"
    >
      <Button @click="openRoleModal()" variant="primary" class="mr-2 shadow-md"> Add Role </Button>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-80 text-slate-500">
          <FormInput
            v-model="search"
            @input="searchRole"
            id="search"
            name="search"
            type="text"
            class="w-80 pr-10 !box"
            placeholder="Search..."
          />
          <Lucide icon="Search" class="absolute inset-y-0 right-0 w-4 h-4 my-auto mr-3" />
        </div>
      </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="col-span-12 overflow-auto intro-y lg:overflow-visible">
      <Table class="border-spacing-y-[4px] border-separate -mt-2">
        <Table.Thead>
          <Table.Tr>
            <Table.Th class="border-b-0 whitespace-nowrap">NAME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">DESCRIPTION</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PERMISSIONS</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr v-for="(role, rkey) in roles" :key="rkey" @click="openRoleModal(role)">
            <Table.Td
              class="box w-80 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ role.label }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ role.description }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ role.permissionsCount }} / {{ permissionTotal }}
            </Table.Td>
            <Table.Td
              @click="
                (event) => {
                  event.stopPropagation();
                }
              "
              :class="[
                'box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600',
                'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',
              ]"
            >
              <div class="flex items-center justify-center">
                <Tippy content="Assign permissions">
                  <a
                    class="flex items-center text-emerald-800 cursor-pointer"
                    @click="openPermissionSlideover(role)"
                  >
                    <Lucide icon="FolderKey" class="w-4 h-4 mr-2" />
                  </a>
                </Tippy>
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger cursor-pointer"
                    @click="openDeleteRoleModal(role)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr v-if="!hasRole">
            <Table.Td
              :colspan="colspan"
              class="box w-full rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              <div class="grid grid-cols-1 p-5 text-center">
                <span class="text-slate-400">No Records</span>
              </div>
            </Table.Td>
          </Table.Tr>
        </Table.Tbody>
      </Table>
    </div>
    <!-- END: Data List -->
    <PaginationComponent
      :totalItems="roleTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange"
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteRole" />
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
  <AddUpdateRoleModal ref="roleModal" @submit="createUpdateRole" />
  <PermissionSlideover ref="permissionSlideoverRef" />
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, Ref, ref } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import Table from '@/components/Base/Table';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import useRoleStore from '@/stores/role';
import AddUpdateRoleModal from '@/components/Modals/AddUpdateRoleModal.vue';
import PaginationComponent from '@/components/PaginationComponent.vue';
import { Role } from '@/composables/models/Role';
import PermissionSlideover from '@/components/PermissionSlideover.vue';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';
import { LABEL } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';

const route = useRoute();
const roleModal = ref<InstanceType<typeof AddUpdateRoleModal> | null>(null);
const deleteModalRef = ref<InstanceType<typeof DeleteModal> | null>(null);
const permissionSlideoverRef = ref<InstanceType<typeof PermissionSlideover> | null>(null);

const notificationRef = ref<NotificationElement>();
const basicNonStickyNotificationToggle = () => {
  notificationRef.value?.showToast();
};
provide('bind[notificationRef]', (el: NotificationElement) => {
  notificationRef.value = el;
});

const search = ref('');
const currentPage = ref(1);
const paginate = ref(10);
const colspan = ref(0);

// STORE
const tenantStore = useTenantStore();
const roleStore = useRoleStore();
const toastStore = useToastStore();

// COMPUTED
const roles = computed(() => roleStore.getRoles);
const roleTotal = computed(() => roleStore.getRoleTotal);
const permissionTotal = computed(() => roleStore.getPermissionTotal);
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

const startRoleCount = computed(() => (roles.value.length > 0 ? 1 : 0));
const hasRole = computed(() => roles.value.length > 0);

//METHOD
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getColspan = () => {
  const thElements = document.querySelectorAll('thead th');
  colspan.value = thElements.length;
};

const handlePageChange = async (page: number) => {
  currentPage.value = page;
  await getRolesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getRolesData = async () => {
  await roleStore.getRolesApi({
    search: search.value,
    sortBy: LABEL,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value,
  });
};

const searchRole = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getRolesData();
  }
};

const openRoleModal = (role: Role | null = null) => {
  roleModal.value?.openRoleModal(role ? role : { label: '', description: '' });
};

const openPermissionSlideover = (role: Role) => {
  permissionSlideoverRef.value?.openSlideover(role);
};

const createUpdateRole = async (
  formData: Ref<{ id: number | null; label: string; description: string }>,
) => {
  if (formData.value.id) {
    await roleStore.updateRoleApi(formData.value.id, {
      label: formData.value.label,
      description: formData.value.description,
    });
  } else {
    await roleStore.createRoleApi({
      label: formData.value.label,
      description: formData.value.description,
    });
  }
  basicNonStickyNotificationToggle();
  await getRolesData();
};

const openDeleteRoleModal = (role: Role) => {
  deleteModalRef.value?.open({ id: role.id, reference: role.label });
};

const deleteRole = async (id: number) => {
  await roleStore.deleteRoleApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getRolesData();
});
</script>
