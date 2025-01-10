<template>
  <div
    class="flex grotesk-page min-h-screen flex-col justify-center items-center px-6 py-12 lg:px-8 bg-gray-50"
  >
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h1 class="text-2xl text-center text-primary font-bold">WellRx</h1>
      <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">
        Account Verification
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div>
        <div class="flex items-center space-x-2">
          <label for="account" class="block text-sm/6 text-gray-900">Account Name</label>
          <Tippy content="Enter an account name. It will be used to verify if a pharmacy exists.">
            <button
              type="button"
              class="flex h-4 w-4 items-center font-medium justify-center rounded-full border text-xs"
              aria-label="Help"
            >
              ?
            </button>
          </Tippy>
        </div>
        <div class="mt-2">
          <input
            type="account"
            name="account"
            id="account"
            autocomplete="account"
            required
            class="block w-full rounded-md border-solid border bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-indigo-500 sm:text-sm/6"
          />
        </div>
      </div>
      <div>
        <button
          type="submit"
          class="mt-5 flex w-full justify-center rounded-md bg-primary-blue px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-secondary-blue focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Verify
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Tippy from '@/components/Base/Tippy';
</script>

<style lang="scss">
@use '@/assets/css/landing-font';
</style>
