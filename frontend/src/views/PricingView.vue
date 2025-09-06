<template>
  <div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
          {{ $t('pricing.title') }}
        </h1>
        <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500 dark:text-gray-400">
          {{ $t('pricing.subtitle') }}
        </p>
      </div>

      <!-- Toggle -->
      <div class="mt-12 flex justify-center">
        <div class="relative flex items-center">
          <span class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">
            {{ $t('pricing.monthly') }}
          </span>
          <button 
            type="button"
            @click="isAnnual = !isAnnual"
            class="bg-indigo-600 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :class="{ 'bg-indigo-600': isAnnual, 'bg-gray-200': !isAnnual }"
            role="switch"
            :aria-checked="isAnnual"
          >
            <span class="sr-only">Toggle billing</span>
            <span 
              aria-hidden="true"
              class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
              :class="{ 'translate-x-5': isAnnual, 'translate-x-0': !isAnnual }"
            ></span>
          </button>
          <span class="ml-3 flex items-center">
            <span class="text-sm font-medium text-gray-900 dark:text-gray-300">
              {{ $t('pricing.annual') }}
            </span>
            <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 rounded-full">
              {{ $t('pricing.save20') }}
            </span>
          </span>
        </div>
      </div>

      <!-- Pricing Cards -->
      <div class="mt-16 space-y-8 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
        <!-- Free Tier -->
        <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
          <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
              {{ $t('pricing.tiers.free.name') }}
            </h2>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
              {{ $t('pricing.tiers.free.description') }}
            </p>
            <p class="mt-8">
              <span class="text-4xl font-extrabold text-gray-900 dark:text-white">
                {{ $t('pricing.tiers.free.price') }}
              </span>
              <span class="text-base font-medium text-gray-500 dark:text-gray-400">
                {{ $t('pricing.perMonth') }}
              </span>
            </p>
            <a 
              href="#" 
              class="mt-8 block w-full bg-gray-800 dark:bg-white border border-transparent rounded-md py-2 text-sm font-semibold text-white dark:text-gray-900 text-center hover:bg-gray-900 dark:hover:bg-gray-100"
            >
              {{ $t('pricing.getStarted') }}
            </a>
          </div>
          <div class="px-6 pt-6 pb-8">
            <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wide">
              {{ $t('pricing.whatsIncluded') }}
            </h3>
            <ul role="list" class="mt-6 space-y-4">
              <li v-for="feature in $tm('pricing.tiers.free.features')" :key="feature" class="flex">
                <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ feature }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Pro Tier -->
        <div class="relative bg-white dark:bg-gray-800 border-2 border-indigo-600 dark:border-indigo-500 rounded-2xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
          <div class="p-6">
            <h2 class="text-lg font-medium text-indigo-600 dark:text-indigo-400">
              {{ $t('pricing.tiers.pro.name') }}
            </h2>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
              {{ $t('pricing.tiers.pro.description') }}
            </p>
            <p class="mt-8">
              <span class="text-4xl font-extrabold text-gray-900 dark:text-white">
                {{ isAnnual ? $t('pricing.tiers.pro.yearlyPrice') : $t('pricing.tiers.pro.monthlyPrice') }}
              </span>
              <span class="text-base font-medium text-gray-500 dark:text-gray-400">
                {{ $t('pricing.perMonth') }}
                <span v-if="isAnnual" class="text-sm">
                  ({{ $t('pricing.billedAnnually') }})
                </span>
              </span>
            </p>
            <a 
              href="#" 
              class="mt-8 block w-full bg-indigo-600 border border-transparent rounded-md py-2 text-sm font-semibold text-white text-center hover:bg-indigo-700"
            >
              {{ $t('pricing.getStarted') }}
            </a>
          </div>
          <div class="px-6 pt-6 pb-8">
            <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wide">
              {{ $t('pricing.whatsIncluded') }}
            </h3>
            <ul role="list" class="mt-6 space-y-4">
              <li v-for="feature in $tm('pricing.tiers.pro.features')" :key="feature" class="flex">
                <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ feature }}</span>
              </li>
            </ul>
          </div>
          <div class="absolute top-0 inset-x-0 bg-indigo-600 dark:bg-indigo-500 px-4 py-1 transform -translate-y-1/2 rounded-full text-xs font-semibold text-white text-center mx-12">
            {{ $t('pricing.mostPopular') }}
          </div>
        </div>

        <!-- Enterprise Tier -->
        <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
          <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
              {{ $t('pricing.tiers.enterprise.name') }}
            </h2>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
              {{ $t('pricing.tiers.enterprise.description') }}
            </p>
            <p class="mt-8">
              <span class="text-4xl font-extrabold text-gray-900 dark:text-white">
                {{ $t('pricing.tiers.enterprise.price') }}
              </span>
            </p>
            <a 
              href="#" 
              class="mt-8 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md py-2 text-sm font-semibold text-gray-900 dark:text-white text-center hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              {{ $t('pricing.contactUs') }}
            </a>
          </div>
          <div class="px-6 pt-6 pb-8">
            <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wide">
              {{ $t('pricing.whatsIncluded') }}
            </h3>
            <ul role="list" class="mt-6 space-y-4">
              <li v-for="feature in $tm('pricing.tiers.enterprise.features')" :key="feature" class="flex">
                <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ feature }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="mt-24">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">
          {{ $t('pricing.faq.title') }}
        </h2>
        <div class="mt-12 max-w-3xl mx-auto space-y-6">
          <div v-for="(faq, index) in $tm('pricing.faq.questions')" :key="index" class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
            <button 
              @click="toggleFaq(index)"
              class="w-full px-6 py-5 text-left focus:outline-none"
              :aria-expanded="activeFaq === index"
              :aria-controls="`faq-${index}`"
            >
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                  {{ faq.question }}
                </h3>
                <svg 
                  class="h-6 w-6 transform transition-transform duration-200" 
                  :class="{ 'rotate-180': activeFaq === index }"
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </button>
            <div 
              v-show="activeFaq === index"
              id="faq-1" 
              class="px-6 pb-5"
            >
              <p class="text-gray-600 dark:text-gray-300">
                {{ faq.answer }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'PricingView',
  setup() {
    const isAnnual = ref(true);
    const activeFaq = ref(null);

    const toggleFaq = (index) => {
      activeFaq.value = activeFaq.value === index ? null : index;
    };

    return {
      isAnnual,
      activeFaq,
      toggleFaq,
    };
  },
};
</script>
