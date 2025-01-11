<template>
  <h2 class="mt-10 text-lg font-medium intro-y">Categories</h2>
  <div class="grid grid-cols-12 gap-6 mt-5">
    <div class="flex flex-wrap items-center col-span-12 mt-2 intro-y sm:flex-nowrap">
      <Button @click="openCategoryModal()" variant="primary" class="mr-2 shadow-md">
        Add Category
      </Button>
      <Menu>
        <Menu.Button :as="Button" class="px-2 !box">
          <span class="flex items-center justify-center w-5 h-5">
            <Lucide icon="Plus" class="w-4 h-4" />
          </span>
        </Menu.Button>
        <Menu.Items class="w-40">
          <Menu.Item> <Lucide icon="FileDown" class="w-4 h-4 mr-2" /> Import </Menu.Item>
          <Menu.Item> <Lucide icon="FileText" class="w-4 h-4 mr-2" /> Export to Excel </Menu.Item>
        </Menu.Items>
      </Menu>
      <div class="hidden mx-auto md:block text-slate-500">Showing {{ startcategoryCount }} to {{ paginate }} of {{ categoryTotal }} entries</div>
      <div class="w-full mt-3 sm:w-auto sm:mt-0 sm:ml-auto md:ml-0">
        <div class="relative w-72 text-slate-500">
          <FormInput v-model="search" @input="searchCategory" type="text" class="w-72 pr-10 !box" placeholder="Search by name or code..." />
          <Lucide icon="Search" class="absolute inset-y-0 right-0 w-4 h-4 my-auto mr-3" />
        </div>
      </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="col-span-12 overflow-auto intro-y lg:overflow-visible">
      <Table class="border-spacing-y-[4px] border-separate -mt-2">
        <Table.Thead>
          <Table.Tr>
            <Table.Th class="border-b-0 whitespace-nowrap">CODE</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">NAME</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">MARKUP </Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">DESCRIPTION</Table.Th>
            <Table.Th class="border-b-0 whitespace-nowrap">PRODUCT COUNT</Table.Th>
            <Table.Th class="text-center border-b-0 whitespace-nowrap">ACTIONS</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          <Table.Tr
            v-for="(category, ckey) in categories"
            :key="ckey"
            class="intro-x"
            @click="openCategoryModal(category)"
          >
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ category.categoryCode }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ category.name }}
            </Table.Td>
            <Table.Td
              class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ category.markup }}
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ category.description }}
            </Table.Td>
            <Table.Td
              class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 cursor-pointer"
            >
              {{ category.productCount }}
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
                <Tippy content="Delete">
                  <a
                    class="flex items-center text-danger"
                    href="#"
                    @click="openDeleteCategoryModal(category)"
                  >
                    <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                  </a>
                </Tippy>
              </div>
            </Table.Td>
          </Table.Tr>
          <Table.Tr
            v-if="!hasCategory"
          >
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
      :totalItems="categoryTotal"
      :initialItemsPerPage="paginate"
      @page-change="handlePageChange"
      @items-per-page-change="handleItemsPerPageChange" 
    />
  </div>
  <DeleteModal ref="deleteModalRef" @submit="deleteCategory" />
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
  <AddUpdateCategoryModal ref="categoryModal" @submit="createUpdateCategory" />
</template>

<script setup lang="ts">
import _ from 'lodash';
import { computed, onMounted, provide, ref } from 'vue';
import Button from '@/components/Base/Button';
import { FormInput } from '@/components/Base/Form';
import Notification from '@/components/Base/Notification';
import { type NotificationElement } from '@/components/Base/Notification';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import { Menu } from '@/components/Base/Headless';
import Table from '@/components/Base/Table';
import useCategoryStore from '@/stores/category';
import AddUpdateCategoryModal from '@/components/Modals/AddUpdateCategoryModal.vue';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import useToastStore from '@/stores/toast';
import NotificationType from '@/enums/notification';
import { Category } from '@/composables/models/Category';
import { Ref } from 'vue';
import { useRoute } from 'vue-router';
import useTenantStore from '@/stores/tenant';
import SortOrder from '@/enums/sortOrder';
import { CATEGORY_CODE } from '@/constants/properties';
import PaginationComponent from '@/components/PaginationComponent.vue';

const route = useRoute();
const categoryModal = ref<InstanceType<typeof AddUpdateCategoryModal> | null>(null);
const deleteModalRef = ref<InstanceType<typeof DeleteModal> | null>(null);

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
const categoryStore = useCategoryStore();
const toastStore = useToastStore();

// COMPUTED
const categories = computed(() => categoryStore.getCategories);
const categoryTotal = computed(() => categoryStore.getCategoryTotal);
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

const startcategoryCount = computed(() => categories.value.length > 0 ? 1 : 0);
const hasCategory = computed(() => categories.value.length > 0);


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
  await getCategoriesData();
};

const handleItemsPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  currentPage.value = 1;
};

const getCategoriesData = async () => {
  await categoryStore.getCategoriesApi({
    search: search.value,
    sortBy: CATEGORY_CODE,
    sortOrder: SortOrder.ASC,
    offset: (currentPage.value - 1) * paginate.value,
    limit: paginate.value
  });
};

const searchCategory = async () => {
  if (search.value.length === 0 || search.value.length >= 3) {
    await getCategoriesData();
  }
}

const openCategoryModal = (category: Category | null = null) => {
  categoryModal.value?.openCategoryModal(
    category ? category : { name: '', markup: 0, description: '' },
  );
};

const createUpdateCategory = async (
  formData: Ref<{ id: number | null; name: string; markup: number; description: string }>,
) => {
  if (formData.value.id) {
    await categoryStore.updateCategoryApi(formData.value.id, {
      name: formData.value.name,
      markup: formData.value.markup,
      description: formData.value.description,
    });
  } else {
    await categoryStore.createCategoryApi({
      name: formData.value.name,
      markup: formData.value.markup,
      description: formData.value.description,
    });
  }
  basicNonStickyNotificationToggle();
  await getCategoriesData();
};

const openDeleteCategoryModal = (category: Category) => {
  deleteModalRef.value?.open({ id: category.id, reference: category.categoryCode });
};

const deleteCategory = async (id: number) => {
  await categoryStore.deleteCategoryApi(id);
};

onMounted(async () => {
  await accountVerification();
  getColspan();
  await getCategoriesData();
});
</script>
