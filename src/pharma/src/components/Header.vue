<template>
  <header class="landing-header">
    <nav class="mx-auto flex w-11/12 items-center justify-between p-16 lg:px-2" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="#" class="m-1.5 p-1.5">
          <span class="text-2xl font-bold">WellRx</span>
          <!-- <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="" /> -->
        </a>
      </div>
      <div class="flex lg:hidden">
        <button
          type="button"
          class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
          @click="mobileMenuOpen = true"
        >
          <span class="sr-only">Open main menu</span>
          <MenuIcon class="size-6" aria-hidden="true" />
        </button>
      </div>
      <PopoverGroup class="hidden lg:flex lg:gap-x-16">
        <a href="#" class="text-sm/6 font-bold text-gray-900">Home</a>
        <a href="#" class="text-sm/6 font-normal hover:font-bold">Features</a>
        <a href="#" class="text-sm/6 font-normal hover:font-bold">Pricing</a>
        <a href="#" class="text-sm/6 font-normal hover:font-bold">Team</a>
        <a href="#" class="text-sm/6 font-normal hover:font-bold">Contact</a>
      </PopoverGroup>
      <div class="items-center hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-8">
        <RouterLink :to="{ name: 'verification' }" class="text-sm/6 font-bold text-gray-900"
          >Sign In</RouterLink
        >
        <button
          type="button"
          @click="goToSignUp"
          class="bg-sd-blue opacity-16 rounded-full border-white sign-up border py-2 px-6 font-normal"
        >
          Sign Up <span aria-hidden="true">&rarr;</span>
        </button>
      </div>
    </nav>
    <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
      <div class="fixed inset-0 z-10" />
      <DialogPanel
        class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10"
      >
        <div class="flex items-center justify-between">
          <a href="#" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
          </a>
          <button
            type="button"
            class="-m-2.5 rounded-md p-2.5 text-gray-700"
            @click="mobileMenuOpen = false"
          >
            <span class="sr-only">Close menu</span>
            <!-- <XIcon class="size-6" aria-hidden="true" /> -->
            <XIcon class="size-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="space-y-2 py-6">
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Home</a
              >
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Features</a
              >
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Pricing</a
              >
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Team</a
              >
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Contact</a
              >
            </div>
            <div class="py-6">
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Sign in</a
              >
              <a
                href="#"
                class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                >Sign Up</a
              >
            </div>
          </div>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Dialog, DialogPanel, PopoverGroup } from '@headlessui/vue'
import { XIcon, MenuIcon } from 'lucide-vue-next'
import { useRouter } from 'vue-router'

const mobileMenuOpen = ref(false)

const router = useRouter()

const goToSignUp = (): void => {
  router.push({ path: '/sign-up' })
}
</script>
