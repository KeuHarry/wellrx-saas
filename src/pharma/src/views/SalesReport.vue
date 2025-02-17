<template>
  <div class="p-6 bg-gray-100 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-xl font-bold text-blue-900">Sales Report Dashboard</h1>
      <p class="text-gray-600">Analyze sales data by report type</p>
    </div>

    <!-- Report Type Selector -->
    <div class="flex justify-between items-center mb-4">
      <div class="w-1/5">
        <label class="block text-sm font-medium text-gray-600">Report Type</label>
        <select
          v-model="selectedReport"
          class="mt-1 block w-full h-10 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option
            v-for="(reportType, tkey) in salesReportType"
            :key="tkey"
            :value="reportType.value"
          >
            {{ reportType.name }}
          </option>
        </select>
      </div>
      <div class="flex items-end gap-2">
        <div>
          <label for="start-date" class="block mr-2 text-sm font-medium text-gray-600">From</label>
          <Litepicker
            v-model="startDate"
            :options="dateOptions"
            placeholder="YYYY-MM-DD"
            class="block w-full h-10 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="end-date" class="block mr-2 text-sm font-medium text-gray-600">To</label>
          <Litepicker
            v-model="endDate"
            :options="dateOptions"
            placeholder="YYYY-MM-DD"
            class="block w-full h-10 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <Button
            @click="fetchReport"
            variant="primary"
            class="w-full text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
          >
            Generate Report
          </Button>
        </div>
        <div>
          <Menu>
            <Menu.Button :as="Button" class="px-2 !box">
              <span class="flex items-center justify-center w-6 h-6">
                <Lucide icon="Plus" class="w-4 h-4" />
              </span>
            </Menu.Button>
            <Menu.Items class="w-40">
              <Menu.Item> <Lucide icon="FileDown" class="w-4 h-4 mr-2" />Export to PDF</Menu.Item>
              <Menu.Item> <Lucide icon="FileDown" class="w-4 h-4 mr-2" />Export to Excel</Menu.Item>
            </Menu.Items>
          </Menu>
        </div>
      </div>
    </div>

    <!-- Report Content -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <!-- Daily/Weekly/Monthly Sales Report -->
      <div v-if="selectedReport === SALE_REPORT">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Sales Report</h2>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                #
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Date
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Salesman
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Product
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Qty Before
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Qty Sold
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Qty After
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                QOH
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Price
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Net Due
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(sale, idx) in paidSales" :key="idx">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ idx + 1 }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sale.paidDate }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sale.salesman }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sale.product }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.quantityBefore }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.quantitySold }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.quantityAfter }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sale.qoh }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sale.price }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.saleItemTotal }}
              </td>
            </tr>
          </tbody>
        </table>
        <PaginationComponent
          :totalItems="paidSalesTotal"
          :initialItemsPerPage="paginate"
          @page-change="handlePaidSalesPageChange"
          @items-per-page-change="handlePaidSalesPerPageChange"
        />
      </div>

      <!-- Prescription Sales Report -->
      <div v-if="selectedReport === 'prescription'">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Prescription Sales Report</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Product
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Quantity
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Total Revenue
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sale in prescriptionSales" :key="sale.product">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ sale.product }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ sale.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ${{ sale.totalRevenue.toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- OTC Sales Report -->
      <div v-if="selectedReport === 'otc'">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">OTC Sales Report</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Product
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Quantity
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Total Revenue
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sale in otcSales" :key="sale.product">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ sale.product }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ sale.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ${{ sale.totalRevenue.toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Profit and Loss Report -->
      <div v-if="selectedReport === 'profitLoss'">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Profit and Loss Report</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Category
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Amount
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total Revenue</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(profitLossData ? profitLossData.totalRevenue : null) }}
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total Cost</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(profitLossData ? profitLossData.totalAcqCost : null) }}
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gross Profit</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(profitLossData ? profitLossData.grossProfit : null) }}
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Taxes</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(profitLossData ? profitLossData.totalTaxes : null) }}
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Net Profit</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(profitLossData ? profitLossData.netProfit : null) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import Button from '@/components/Base/Button';
import Litepicker from '@/components/Base/Litepicker';
import { dateFormat } from '@/constants/dateFormat';
import { Menu } from '@/components/Base/Headless';
import Lucide from '@/components/Base/Lucide';
import useReportStore from '@/stores/report';
import SortOrder from '@/enums/sortOrder';
import { defaultItemsPerPage } from '@/constants/pagination';
import { UPDATED_AT } from '@/constants/properties';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';
import { PROFIT_LOSS, SALE_REPORT, salesReportType } from '@/constants/salesReportType';
import PaginationComponent from '@/components/PaginationComponent.vue';
import { formatPrice } from '@/utils/helper';

const route = useRoute();
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

// Report type selector
const selectedReport = ref<string>('dailyWeeklyMonthly');

// Filters
const startDate = ref<string>('');
const endDate = ref<string>('');

const paidSalesCurrentPage = ref(1);
const paginate = ref(defaultItemsPerPage);

const prescriptionSales = ref<any[]>([
  { product: 'Medication A', quantity: 10, totalRevenue: 500 },
  { product: 'Medication B', quantity: 5, totalRevenue: 250 },
]);

const otcSales = ref<any[]>([
  { product: 'OTC Product A', quantity: 20, totalRevenue: 300 },
  { product: 'OTC Product B', quantity: 15, totalRevenue: 225 },
]);

// STORE
const reportStore = useReportStore();
const tenantStore = useTenantStore();

// COMPUTED
const paidSales = computed(() => reportStore.getPaidSalesReport);
const paidSalesTotal = computed(() => reportStore.getPaidSalesReportTotal);
const profitLossData = computed(() => reportStore.getProfitLossReport);

// Fetch report data (mock implementation)
const accountVerification = async () => {
  await tenantStore.accountVerificationApi(route.params.identifier as string);
};
const fetchReport = async () => {
  switch (selectedReport.value) {
    case SALE_REPORT:
      await getPaidSalesReport();
      break;
    case PROFIT_LOSS:
      await getProfitLossReport();
      break;
    default:
      break;
  }
};

const handlePaidSalesPageChange = async (page: number) => {
  paidSalesCurrentPage.value = page;
  await getPaidSalesReport();
};

const handlePaidSalesPerPageChange = (itemsPerPage: number) => {
  paginate.value = itemsPerPage;
  paidSalesCurrentPage.value = 1;
};

const getPaidSalesReport = async () => {
  await reportStore.getPaidSalesReportApi({
    sortBy: UPDATED_AT,
    sortOrder: SortOrder.DESC,
    offset: (paidSalesCurrentPage.value - 1) * paginate.value,
    limit: paginate.value,
    startDate: startDate.value,
    endDate: endDate.value,
  });
};

const getProfitLossReport = async () => {
  await reportStore.getProfitLossReportApi({
    startDate: startDate.value,
    endDate: endDate.value,
  });
};

// Export to CSV
const exportToCSV = () => {
  console.log('Exporting to CSV...');
};

// Export to PDF
const exportToPDF = () => {
  console.log('Exporting to PDF...');
};

onMounted(async () => {
  await accountVerification();
  await getPaidSalesReport();
});
</script>
