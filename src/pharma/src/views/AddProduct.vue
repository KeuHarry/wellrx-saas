<template>
  <div class="grid grid-cols-1 backdrop-blur-xl bg-white/50 w-full">
    <div class="col-span-12 h-14">
      <a @click="closeAddProductForm" class="absolute top-0 right-0 mt-3 mr-3">
        <Tippy content="Close">
          <Lucide icon="X" class="w-8 h-8 text-slate-700 cursor-pointer stroke-1" />
        </Tippy>
      </a>
    </div>
    <div class="grid grid-cols-12 gap-4 m-5">
      <div class="col-span-8 p-5 bg-white border rounded-md shadow-sm">
        <h2 class="text-slate-900 pb-5 font-medium">General Information</h2>
        <div class="grid grid-cols-12 gap-4">
          <div class="col-span-10">
            <label for="product-name" class="block text-sm/6 text-gray-600">Product Name</label>
            <input
              type="text"
              placeholder="Enter product name"
              name="product-name"
              id="product-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="product-name" class="block text-sm/6 text-gray-600">Type</label>
            <div class="flex items-center h-9 gap-6">
              <div v-for="type in productTypes" :key="type.id" class="space-x-2 text-center">
                <input
                  type="radio"
                  :value="type.value"
                  v-model="selectedProductType"
                  :id="'id-' + type.id"
                  class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
                <label class="mr-4">{{ type.name }}</label>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <label for="description" class="block text-sm/6 text-gray-600">Description</label>
          <textarea
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="description"
            id="description"
          >
          </textarea>
        </div>
        <div class="grid grid-cols-12 gap-4 mt-4">
          <div class="col-span-4">
            <label for="stock-id" class="block text-sm/6 text-gray-600">Stock ID #</label>
            <input
              disabled
              type="text"
              placeholder="Enter stock ID"
              name="stock-id"
              id="stock-id"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-100 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="barcode" class="block text-sm/6 text-gray-600">Barcode</label>
            <input
              type="text"
              placeholder="Enter barcode"
              name="barcode"
              id="barcode"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="cip" class="block text-sm/6 text-gray-600">CIP #</label>
            <input
              type="text"
              placeholder="Enter CIP"
              name="cip"
              id="cip"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-12 gap-4 mt-4">
          <div class="col-span-3 flex items-center space-x-2">
            <input
              type="checkbox"
              id="is-generic"
              name="is-generic"
              class="border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
            <label for="is-generic" class="mr-4">Generic</label>
          </div>
          <div class="col-span-9">
            <label for="generic-product" class="block text-sm/6 text-gray-600"
              >Generic Product</label
            >
            <TomSelect
              v-model="select"
              :options="{
                placeholder: 'Search generic product',
              }"
              class="w-full h-9 border-gray-300 rounded-md shadow-sm text-sm text-slate-900 sm:text-sm/6"
            >
              <option value="1">Leonardo DiCaprio</option>
              <option value="2">Johnny Deep</option>
              <option value="3">Robert Downey, Jr</option>
              <option value="4">Samuel L. Jackson</option>
              <option value="5">Morgan Freeman</option>
            </TomSelect>
          </div>
        </div>
      </div>
      <div class="col-span-4 p-5 bg-white border rounded-md shadow-sm"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Lucide from '@/components/Base/Lucide';
import Tippy from '@/components/Base/Tippy';
import { OTC, productTypes } from '@/constants/productType';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import TomSelect from '@/components/Base/TomSelect';

const router = useRouter();
const route = useRoute();

const select = ref('1');
const selectedProductType = ref<string>(OTC);

// METHOD
const closeAddProductForm = () => {
  router.push({
    name: 'product-list',
    params: { identifier: route.params.identifier },
  });
};
</script>
