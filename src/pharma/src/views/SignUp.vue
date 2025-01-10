<template>
  <div class="mx-auto w-11/12 lg:px-2 grotesk-page">
    <div class="w-auto pt-16 pb-3">
      <a href="/" class="m-1.5 p-1.5">
        <span class="text-2xl text-primary font-bold">WellRx</span>
        <!-- <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="" /> -->
      </a>
    </div>
    <div class="grid grid-cols-2 gap-8 mt-20">
      <div class="grid grid-cols-1">
        <div class="flex ml-20">
          <img class="w-auto" src="@/assets/images/doctors-registration.svg" alt="doctors" />
        </div>
      </div>
      <div class="grid grid-cols-1 sm:mr-48 sm:ml-12">
        <form @submit.prevent="handleRegistration">
          <h1 class="text-28 font-bold">Sign Up</h1>
          <p class="text-pretty text-lg font-light sm:text-lg/8 mb-12">
            Join more than 1000 satisfied pharmacies.
          </p>
          <div class="mt-4 grid grid-cols-1 gap-x-3 sm:grid-cols-5">
            <div class="sm:col-span-3">
              <label for="pharma-name" class="block text-sm/6 text-gray-900">Pharmacy Name</label>
              <input
                v-model="formData.pharmacyName"
                type="text"
                placeholder="Ex. Wellcheck Pharmacy"
                name="pharma-name"
                id="pharma-name"
                class="block w-full h-9 border rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.pharmacyName.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.pharmacyName.$error" class="text-red-500">{{
                validation.pharmacyName.$errors[0].$message
              }}</span>
            </div>
            <div class="sm:col-span-2">
              <label for="reg-number" class="block text-sm/6 text-gray-900"
                >Business Registration Number</label
              >
              <input
                v-model="formData.registrationNumber"
                type="text"
                placeholder="Enter registration Number"
                name="reg-number"
                id="reg-number"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[
                  validation.registrationNumber.$error ? 'border-red-500' : 'border-gray-300',
                ]"
              />
              <span v-if="validation.registrationNumber.$error" class="text-red-500">{{
                validation.registrationNumber.$errors[0].$message
              }}</span>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-1 gap-x-3 sm:grid-cols-5">
            <div class="sm:col-span-3">
              <label for="address" class="block text-sm/6 text-gray-900">Address</label>
              <input
                v-model="formData.pharmacyAddress"
                type="text"
                placeholder="Enter address"
                name="address"
                id="address"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.pharmacyAddress.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.pharmacyAddress.$error" class="text-red-500">{{
                validation.pharmacyAddress.$errors[0].$message
              }}</span>
            </div>
            <div class="sm:col-span-2">
              <label for="country" class="block text-sm/6 text-gray-900">Country</label>
              <input
                @input="showCountryDropdown"
                name="country"
                id="country"
                type="text"
                v-model="formData.pharmacyCountry"
                placeholder="Search for a country"
                class="w-full h-9 border rounded-lg shadow-sm text-sm sm:text-sm/6 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500"
                :class="[validation.pharmacyCountry.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.pharmacyCountry.$error" class="text-red-500">{{
                validation.pharmacyCountry.$errors[0].$message
              }}</span>

              <!-- Dropdown List -->
              <ul
                v-if="filteredCountries.length > 0 && formData.pharmacyCountry && !isDropdownClosed"
                class="absolute z-10 w-auto bg-white border border-gray-200 rounded-lg shadow-sm mt-1 max-h-60 overflow-auto"
              >
                <li
                  v-for="(country, index) in filteredCountries"
                  :key="index"
                  @click="selectCountry(country)"
                  class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-gray-700"
                >
                  {{ country.name }}
                </li>
              </ul>

              <!-- No Results -->
              <div v-else-if="filteredCountries.length <= 0" class="mt-2 text-gray-500 text-sm">
                No countries found.
              </div>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-1 gap-x-3 sm:grid-cols-6">
            <div class="sm:col-span-2">
              <label for="account" class="block text-sm/6 text-gray-900">Account Name</label>
              <input
                v-model="formData.account"
                type="text"
                placeholder="Enter account Name"
                name="account"
                id="account"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.account.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.account.$error" class="text-red-500">{{
                validation.account.$errors[0].$message
              }}</span>
            </div>
            <div class="sm:col-span-2">
              <label for="email" class="block text-sm/6 text-gray-900">Email</label>
              <input
                v-model="formData.email"
                type="email"
                placeholder="Enter Email"
                name="email"
                id="email"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.email.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.email.$error" class="text-red-500">{{
                validation.email.$errors[0].$message
              }}</span>
            </div>
            <div class="sm:col-span-2">
              <label for="phone" class="block text-sm/6 text-gray-900">Contact Number</label>
              <input
                v-model="formData.phoneNumber"
                type="text"
                placeholder="+1xxx"
                name="phone"
                id="phone"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.phoneNumber.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.phoneNumber.$error" class="text-red-500">{{
                validation.phoneNumber.$errors[0].$message
              }}</span>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-1 gap-x-3 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="password" class="block text-sm/6 text-gray-900">Password</label>
              <input
                v-model="formData.password"
                type="password"
                placeholder="Enter password"
                name="password"
                id="password"
                class="block w-full h-9 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.password.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.password.$error" class="text-red-500">{{
                validation.password.$errors[0].$message
              }}</span>
            </div>
            <div class="sm:col-span-3">
              <label for="confirm-password" class="block text-sm/6 text-gray-900"
                >Confirm Password</label
              >
              <input
                v-model="formData.confirmPassword"
                type="password"
                placeholder="Confirm password"
                name="confirm-password"
                id="confirm-password"
                class="block w-full h-9 border rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
                :class="[validation.confirmPassword.$error ? 'border-red-500' : 'border-gray-300']"
              />
              <span v-if="validation.confirmPassword.$error" class="text-red-500">{{
                validation.confirmPassword.$errors[0].$message
              }}</span>
            </div>
          </div>
          <div class="mt-5 grid grid-cols-1 gap-x-3 items-center justify-center">
            <button
              type="submit"
              class="bg-primary-blue w-full text-white rounded-full border py-2 px-6 font-normal"
            >
              Register
            </button>
            <p class="text-center">
              or <a class="text-blue-link" href="#">Log in to your account</a>
            </p>
          </div>
          <div class="mt-10 grid grid-cols-1 gap-x-3 items-center justify-center">
            <p class="text-center">
              By using WellRx, you are agreeing to our
              <a class="text-blue-link" href="#">Terms of service</a> and
              <a class="text-blue-link" href="#">privacy policy</a>.
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import useSubscriptionStore from '@/stores/subscription';
import { Country } from '@/types/country';
import { countries } from 'countries-list';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import { getEnv } from '@/utils/getEnv';
import { useVuelidate } from '@vuelidate/core';
import { required, email, sameAs, minLength, helpers } from '@vuelidate/validators';
import { reactive } from 'vue';
import useTenantStore from '@/stores/tenant';
import { useRouter } from 'vue-router';

const countryList: Country[] = Object.values(countries) as Country[];

// Reactive variables
const selectedCountry = ref('');
const isDropdownClosed = ref(false);
const publicKey = getEnv('STRIPE_KEY');
const stripePromise = loadStripe(publicKey);
const isLoading = ref(false);

const router = useRouter();

const formData = reactive({
  pharmacyName: '',
  registrationNumber: '',
  pharmacyAddress: '',
  pharmacyCountry: '',
  account: '',
  email: '',
  phoneNumber: '',
  password: '',
  confirmPassword: '',
});

const rules = computed(() => {
  return {
    pharmacyName: { required: helpers.withMessage('Pharmacy name is required', required) },
    registrationNumber: { required },
    pharmacyAddress: { required: helpers.withMessage('Address is required', required) },
    pharmacyCountry: { required: helpers.withMessage('Country is required', required) },
    account: { required: helpers.withMessage('Account name is required', required) },
    email: {
      required: helpers.withMessage('Email is required', required),
      email,
    },
    phoneNumber: { required: helpers.withMessage('Contact number is required', required) },
    password: {
      required: helpers.withMessage('Password is required', required),
      minLength: minLength(8),
    },
    confirmPassword: {
      required: helpers.withMessage('Confirmation is required', required),
      sameAs: sameAs(formData.password),
    },
  };
});

const validation = useVuelidate(rules, formData);

// store
const subscriptionStore = useSubscriptionStore();
const tenantStore = useTenantStore();

// COMPUTED
const filteredCountries = computed(() => {
  return countryList.filter((country) =>
    country.name.toLowerCase().includes(formData.pharmacyCountry.toLowerCase()),
  );
});

const sessionId = computed(() => subscriptionStore.getStripeSessionId);

// Select a country
const selectCountry = (country: Country) => {
  selectedCountry.value = country.name;
  formData.pharmacyCountry = country.name;
  isDropdownClosed.value = true;
};

const showCountryDropdown = () => {
  isDropdownClosed.value = false;
};

// Close dropdown when clicking outside
const closeDropdown = (event: MouseEvent) => {
  const dropdown = document.querySelector('.w-full'); // Adjust the class selector as needed
  const inputField = document.querySelector('#country'); // Adjust the selector as needed
  if (
    dropdown &&
    !dropdown.contains(event.target as Node) &&
    !inputField?.contains(event.target as Node)
  ) {
    isDropdownClosed.value = true;
  }
};

const submit = async () => {
  const stripe = await stripePromise;

  if (!stripe) {
    console.error('Stripe failed to initialize.');
    return;
  }

  try {
    // Redirect to Stripe's hosted checkout page
    const { error } = await stripe.redirectToCheckout({ sessionId: sessionId.value });

    if (error) {
      console.error('Stripe Checkout error:', error.message);
    }
  } catch (error) {
    console.error('Error redirecting to Stripe:', error);
  }
};

const handleRegistration = async () => {
  try {
    const result = await validation.value.$validate();
    if (!result) {
      return;
    }
    isLoading.value = true;
    await registerTenant();
    isLoading.value = false;
  } catch (error) {
    isLoading.value = false;
    throw error;
  }
};

const registerTenant = async () => {
  await tenantStore.registerTenantApi({
    pharmacyName: formData.pharmacyName,
    registrationNumber: formData.registrationNumber,
    account: formData.account,
    email: formData.email,
    pharmacyCountry: formData.pharmacyCountry,
    pharmacyAddress: formData.pharmacyAddress,
    phoneNumber: formData.phoneNumber,
    password: formData.password,
  });
  goToLogin();
};

const goToLogin = (): void => {
  if (tenantStore.getTenantIdentifier) {
    router.push({
      name: 'login',
      params: { identifier: tenantStore.getTenantIdentifier },
    });
  }
};

onMounted(async () => {
  document.addEventListener('click', closeDropdown);
  //await subscriptionStore.createSessionApi('price_1QXKWzLt3tZinhYdQGxh9rr2')
});

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdown);
});
</script>
<style lang="scss">
@use '@/assets/css/landing-font';
</style>
