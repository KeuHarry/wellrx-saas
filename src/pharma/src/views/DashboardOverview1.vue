<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-9">
      <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: General Report -->
        <div class="col-span-12 mt-8">
          <div class="flex items-center h-10 intro-y">
            <h2 class="mr-5 text-lg font-medium truncate">General Report</h2>
            <a href="" class="flex items-center ml-auto text-primary">
              <Lucide icon="RefreshCcw" class="w-4 h-4 mr-3" /> Reload Data
            </a>
          </div>
          <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
              <div
                :class="[
                  'relative zoom-in',
                  'before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[\'\']',
                ]"
              >
                <div class="p-5 box">
                  <div class="flex">
                    <Lucide icon="ShoppingCart" class="w-[28px] h-[28px] text-primary" />
                    <div class="ml-auto">
                      <Tippy
                        as="div"
                        class="cursor-pointer bg-success py-[3px] flex rounded-full text-white text-xs pl-2 pr-1 items-center font-medium"
                        content="33% Higher than last month"
                      >
                        33%
                        <Lucide icon="ChevronUp" class="w-4 h-4 ml-0.5" />
                      </Tippy>
                    </div>
                  </div>
                  <div class="mt-6 text-3xl font-medium leading-8">{{ resourcesCount?.itemsSoldCount }}</div>
                  <div class="mt-1 text-base text-slate-500">Items Sold</div>
                </div>
              </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
              <div
                :class="[
                  'relative zoom-in',
                  'before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[\'\']',
                ]"
              >
                <div class="p-5 box">
                  <div class="flex">
                    <Lucide icon="CreditCard" class="w-[28px] h-[28px] text-pending" />
                    <div class="ml-auto">
                      <Tippy
                        as="div"
                        class="cursor-pointer bg-danger py-[3px] flex rounded-full text-white text-xs pl-2 pr-1 items-center font-medium"
                        content="2% Lower than last month"
                      >
                        2%
                        <Lucide icon="ChevronDown" class="w-4 h-4 ml-0.5" />
                      </Tippy>
                    </div>
                  </div>
                  <div class="mt-6 text-3xl font-medium leading-8">{{ resourcesCount?.ordersCount }}</div>
                  <div class="mt-1 text-base text-slate-500">New Orders</div>
                </div>
              </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
              <div
                :class="[
                  'relative zoom-in',
                  'before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[\'\']',
                ]"
              >
                <div class="p-5 box">
                  <div class="flex">
                    <Lucide icon="Monitor" class="w-[28px] h-[28px] text-warning" />
                    <div class="ml-auto">
                      <Tippy
                        as="div"
                        class="cursor-pointer bg-success py-[3px] flex rounded-full text-white text-xs pl-2 pr-1 items-center font-medium"
                        content="12% Higher than last month"
                      >
                        12%
                        <Lucide icon="ChevronUp" class="w-4 h-4 ml-0.5" />
                      </Tippy>
                    </div>
                  </div>
                  <div class="mt-6 text-3xl font-medium leading-8">{{ resourcesCount?.productsCount }}</div>
                  <div class="mt-1 text-base text-slate-500">Total Products</div>
                </div>
              </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
              <div
                :class="[
                  'relative zoom-in',
                  'before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[\'\']',
                ]"
              >
                <div class="p-5 box">
                  <div class="flex">
                    <Lucide icon="Clipboard" class="w-[28px] h-[28px] text-success" />
                    <div class="ml-auto">
                      <Tippy
                        as="div"
                        class="cursor-pointer bg-success py-[3px] flex rounded-full text-white text-xs pl-2 pr-1 items-center font-medium"
                        content="22% Higher than last month"
                      >
                        22%
                        <Lucide icon="ChevronUp" class="w-4 h-4 ml-0.5" />
                      </Tippy>
                    </div>
                  </div>
                  <div class="mt-6 text-3xl font-medium leading-8">{{ resourcesCount?.prescriptionsCount }}</div>
                  <div class="mt-1 text-base text-slate-500">Prescriptions</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END: General Report -->
        <!-- BEGIN: Sales Report -->
        <div class="col-span-12 mt-8 lg:col-span-6">
          <div class="items-center block h-10 intro-y sm:flex">
            <h2 class="mr-5 text-lg font-medium truncate">Sales Report</h2>
            <div class="relative mt-3 sm:ml-auto sm:mt-0 text-slate-500">
              <Lucide icon="Calendar" class="absolute inset-y-0 left-0 z-10 w-4 h-4 my-auto ml-3" />
              <Litepicker
                v-model="salesReportFilter"
                :options="{
                  autoApply: false,
                  singleMode: false,
                  numberOfColumns: 2,
                  numberOfMonths: 2,
                  showWeekNumbers: true,
                  dropdowns: {
                    minYear: 1990,
                    maxYear: null,
                    months: true,
                    years: true,
                  },
                }"
                class="pl-10 sm:w-56 !box"
              />
            </div>
          </div>
          <div class="p-5 mt-12 intro-y box sm:mt-5">
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="flex">
                <div>
                  <div class="text-lg font-medium text-primary dark:text-slate-300 xl:text-xl">
                    $15,000
                  </div>
                  <div class="mt-0.5 text-slate-500">This Month</div>
                </div>
                <div
                  class="w-px h-12 mx-4 border border-r border-dashed border-slate-200 dark:border-darkmode-300 xl:mx-5"
                ></div>
                <div>
                  <div class="text-lg font-medium text-slate-500 xl:text-xl">$10,000</div>
                  <div class="mt-0.5 text-slate-500">Last Month</div>
                </div>
              </div>
              <Menu class="mt-5 md:ml-auto md:mt-0">
                <Menu.Button :as="Button" variant="outline-secondary" class="font-normal">
                  Filter by Category
                  <Lucide icon="ChevronDown" class="w-4 h-4 ml-2" />
                </Menu.Button>
                <Menu.Items class="w-40 h-32 overflow-y-auto">
                  <Menu.Item>PC & Laptop</Menu.Item>
                  <Menu.Item>Smartphone</Menu.Item>
                  <Menu.Item>Electronic</Menu.Item>
                  <Menu.Item>Photography</Menu.Item>
                  <Menu.Item>Sport</Menu.Item>
                </Menu.Items>
              </Menu>
            </div>
            <div
              :class="[
                'relative',
                'before:content-[\'\'] before:block before:absolute before:w-16 before:left-0 before:top-0 before:bottom-0 before:ml-10 before:mb-7 before:bg-gradient-to-r before:from-white before:via-white/80 before:to-transparent before:dark:from-darkmode-600',
                'after:content-[\'\'] after:block after:absolute after:w-16 after:right-0 after:top-0 after:bottom-0 after:mb-7 after:bg-gradient-to-l after:from-white after:via-white/80 after:to-transparent after:dark:from-darkmode-600',
              ]"
            >
              <ReportLineChart :height="275" class="mt-6 -mb-6" />
            </div>
          </div>
        </div>
        <!-- END: Sales Report -->
        <!-- BEGIN: Weekly Top Seller -->
        <div class="col-span-12 mt-8 sm:col-span-6 lg:col-span-3">
          <div class="flex items-center h-10 intro-y">
            <h2 class="mr-5 text-lg font-medium truncate">Cashier Performance</h2>
          </div>
          <div class="p-5 mt-5 intro-y box">
            <div class="mt-3">
              <ReportPieChart :height="213" />
            </div>
            <div class="mx-auto mt-8 w-52 sm:w-auto">
              <div class="flex items-center">
                <div class="w-2 h-2 mr-3 rounded-full bg-primary"></div>
                <span class="truncate">17 - 30 Years old</span>
                <span class="ml-auto font-medium">62%</span>
              </div>
              <div class="flex items-center mt-4">
                <div class="w-2 h-2 mr-3 rounded-full bg-pending"></div>
                <span class="truncate">31 - 50 Years old</span>
                <span class="ml-auto font-medium">33%</span>
              </div>
              <div class="flex items-center mt-4">
                <div class="w-2 h-2 mr-3 rounded-full bg-warning"></div>
                <span class="truncate">&gt;= 50 Years old</span>
                <span class="ml-auto font-medium">10%</span>
              </div>
            </div>
          </div>
        </div>
        <!-- END: Weekly Top Seller -->
        <!-- BEGIN: Sales Report -->
        <div class="col-span-12 mt-8 sm:col-span-6 lg:col-span-3">
          <div class="flex items-center h-10 intro-y">
            <h2 class="mr-5 text-lg font-medium truncate">Sales Report</h2>
            <a href="" class="ml-auto truncate text-primary"> Show More </a>
          </div>
          <div class="p-5 mt-5 intro-y box">
            <div class="mt-3">
              <ReportDonutChart :height="213" />
            </div>
            <div class="mx-auto mt-8 w-52 sm:w-auto">
              <div class="flex items-center">
                <div class="w-2 h-2 mr-3 rounded-full bg-primary"></div>
                <span class="truncate">17 - 30 Years old</span>
                <span class="ml-auto font-medium">62%</span>
              </div>
              <div class="flex items-center mt-4">
                <div class="w-2 h-2 mr-3 rounded-full bg-pending"></div>
                <span class="truncate">31 - 50 Years old</span>
                <span class="ml-auto font-medium">33%</span>
              </div>
              <div class="flex items-center mt-4">
                <div class="w-2 h-2 mr-3 rounded-full bg-warning"></div>
                <span class="truncate">&gt;= 50 Years old</span>
                <span class="ml-auto font-medium">10%</span>
              </div>
            </div>
          </div>
        </div>
        <!-- END: Sales Report -->
        <!-- BEGIN: Weekly Top Products -->
        <div class="col-span-12 mt-6">
          <div class="items-center block h-10 intro-y sm:flex">
            <h2 class="mr-5 text-lg font-medium truncate">Weekly Top Products</h2>
            <div class="flex items-center mt-3 sm:ml-auto sm:mt-0">
              <Button class="flex items-center !box text-slate-600 dark:text-slate-300">
                <Lucide icon="FileText" class="hidden w-4 h-4 mr-2 sm:block" />
                Export to Excel
              </Button>
              <Button class="flex items-center ml-3 !box text-slate-600 dark:text-slate-300">
                <Lucide icon="FileText" class="hidden w-4 h-4 mr-2 sm:block" />
                Export to PDF
              </Button>
            </div>
          </div>
          <div class="mt-8 overflow-auto intro-y lg:overflow-visible sm:mt-0">
            <Table class="border-spacing-y-[10px] border-separate sm:mt-2">
              <Table.Thead>
                <Table.Tr>
                  <Table.Th class="border-b-0 whitespace-nowrap"> IMAGES </Table.Th>
                  <Table.Th class="border-b-0 whitespace-nowrap"> PRODUCT NAME </Table.Th>
                  <Table.Th class="text-center border-b-0 whitespace-nowrap"> STOCK </Table.Th>
                  <Table.Th class="text-center border-b-0 whitespace-nowrap"> STATUS </Table.Th>
                  <Table.Th class="text-center border-b-0 whitespace-nowrap"> ACTIONS </Table.Th>
                </Table.Tr>
              </Table.Thead>
              <Table.Tbody>
                <Table.Tr
                  v-for="(faker, fakerKey) in _.take(fakerData, 4)"
                  :key="fakerKey"
                  class="intro-x"
                >
                  <Table.Td
                    class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
                  >
                    <div class="flex">
                      <div class="w-10 h-10 image-fit zoom-in">
                        <Tippy
                          as="img"
                          alt="Midone Tailwind HTML Admin Template"
                          class="rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]"
                          :src="faker.images[0]"
                          :content="`Uploaded at ${faker.dates[0]}`"
                        />
                      </div>
                      <div class="w-10 h-10 -ml-5 image-fit zoom-in">
                        <Tippy
                          as="img"
                          alt="Midone Tailwind HTML Admin Template"
                          class="rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]"
                          :src="faker.images[1]"
                          :content="`Uploaded at ${faker.dates[1]}`"
                        />
                      </div>
                      <div class="w-10 h-10 -ml-5 image-fit zoom-in">
                        <Tippy
                          as="img"
                          alt="Midone Tailwind HTML Admin Template"
                          class="rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]"
                          :src="faker.images[2]"
                          :content="`Uploaded at ${faker.dates[2]}`"
                        />
                      </div>
                    </div>
                  </Table.Td>
                  <Table.Td
                    class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
                  >
                    <a href="" class="font-medium whitespace-nowrap">
                      {{ faker.products[0].name }}
                    </a>
                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                      {{ faker.products[0].category }}
                    </div>
                  </Table.Td>
                  <Table.Td
                    class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
                  >
                    {{ faker.stocks[0] }}
                  </Table.Td>
                  <Table.Td
                    class="box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600"
                  >
                    <div
                      :class="[
                        'flex items-center justify-center',
                        { 'text-success': faker.trueFalse[0] },
                        { 'text-danger': !faker.trueFalse[0] },
                      ]"
                    >
                      <Lucide icon="CheckSquare" class="w-4 h-4 mr-2" />
                      {{ faker.trueFalse[0] ? 'Active' : 'Inactive' }}
                    </div>
                  </Table.Td>
                  <Table.Td
                    :class="[
                      'box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600',
                      'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',
                    ]"
                  >
                    <div class="flex items-center justify-center">
                      <a class="flex items-center mr-3" href="">
                        <Lucide icon="CheckSquare" class="w-4 h-4 mr-1" />
                        Edit
                      </a>
                      <a class="flex items-center text-danger" href="">
                        <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
                        Delete
                      </a>
                    </div>
                  </Table.Td>
                </Table.Tr>
              </Table.Tbody>
            </Table>
          </div>
          <div class="flex flex-wrap items-center mt-3 intro-y sm:flex-row sm:flex-nowrap">
            <Pagination class="w-full sm:w-auto sm:mr-auto">
              <Pagination.Link>
                <Lucide icon="ChevronsLeft" class="w-4 h-4" />
              </Pagination.Link>
              <Pagination.Link>
                <Lucide icon="ChevronLeft" class="w-4 h-4" />
              </Pagination.Link>
              <Pagination.Link>...</Pagination.Link>
              <Pagination.Link>1</Pagination.Link>
              <Pagination.Link active>2</Pagination.Link>
              <Pagination.Link>3</Pagination.Link>
              <Pagination.Link>...</Pagination.Link>
              <Pagination.Link>
                <Lucide icon="ChevronRight" class="w-4 h-4" />
              </Pagination.Link>
              <Pagination.Link>
                <Lucide icon="ChevronsRight" class="w-4 h-4" />
              </Pagination.Link>
            </Pagination>
            <FormSelect class="w-20 mt-3 !box sm:mt-0">
              <option>10</option>
              <option>25</option>
              <option>35</option>
              <option>50</option>
            </FormSelect>
          </div>
        </div>
        <!-- END: Weekly Top Products -->
      </div>
    </div>
    <div class="col-span-12 2xl:col-span-3">
      <div class="pb-10 -mb-10 2xl:border-l">
        <div class="grid grid-cols-12 2xl:pl-6 gap-x-6 2xl:gap-x-0 gap-y-6">
          <!-- BEGIN: Transactions -->
          <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
            <div class="flex items-center h-10 intro-x">
              <h2 class="mr-5 text-lg font-medium truncate">Weekly Best Sellers</h2>
            </div>
            <div class="mt-5">
            <div v-for="(faker, fakerKey) in _.take(fakerData, 4)" :key="fakerKey" class="intro-y">
              <div class="flex items-center px-4 py-4 mb-3 box zoom-in">
                <div class="flex-none w-10 h-10 overflow-hidden rounded-md image-fit">
                  <img alt="Midone Tailwind HTML Admin Template" :src="faker.photos[0]" />
                </div>
                <div class="ml-4 mr-auto">
                  <div class="font-medium">{{ faker.users[0].name }}</div>
                  <div class="text-slate-500 text-xs mt-0.5">
                    {{ faker.dates[0] }}
                  </div>
                </div>
                <div
                  class="px-2 py-1 text-xs font-medium text-white rounded-full cursor-pointer bg-success"
                >
                  137 Sales
                </div>
              </div>
            </div>
            <a
              href=""
              class="block w-full py-4 text-center border border-dotted rounded-md intro-y border-slate-400 dark:border-darkmode-300 text-slate-500"
            >
              View More
            </a>
          </div>
          </div>
          <!-- END: Transactions -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import _ from 'lodash';
import { ref, provide, onMounted, computed } from 'vue';
import fakerData from '@/utils/faker';
import Button from '@/components/Base/Button';
import Pagination from '@/components/Base/Pagination';
import { FormSelect } from '@/components/Base/Form';
import TinySlider, { type TinySliderElement } from '@/components/Base/TinySlider';
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import Litepicker from '@/components/Base/Litepicker';
import ReportDonutChart from '@/components/ReportDonutChart';
import ReportLineChart from '@/components/ReportLineChart';
import ReportPieChart from '@/components/ReportPieChart';
import { Menu } from '@/components/Base/Headless';
import Table from '@/components/Base/Table';
import useReportStore from '@/stores/report';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';

const salesReportFilter = ref<string>('');
const importantNotesRef = ref<TinySliderElement>();

provide('bind[importantNotesRef]', (el: TinySliderElement) => {
  importantNotesRef.value = el;
});

const route = useRoute();

const tenantStore = useTenantStore();
const reportStore = useReportStore();

const resourcesCount = computed(() => reportStore.getResourcesCount);

const prevImportantNotes = () => {
  importantNotesRef.value?.tns.goTo('prev');
};
const nextImportantNotes = () => {
  importantNotesRef.value?.tns.goTo('next');
};

const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};

const getResourcesCountData = async () => {
  await reportStore.getResourcesCountApi();
};

onMounted(async () => {
  await accountVerification();
  await getResourcesCountData();
});
</script>
