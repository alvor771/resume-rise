<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-2">
      <li>
        <div>
          <router-link 
            :to="{ name: 'Home' }" 
            class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
          >
            <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
            <span class="sr-only">{{ $t('common.home') }}</span>
          </router-link>
        </div>
      </li>
      
      <li v-for="(item, index) in breadcrumbs" :key="item.name">
        <div class="flex items-center">
          <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400 dark:text-gray-500" aria-hidden="true" />
          <router-link
            v-if="index < breadcrumbs.length - 1"
            :to="item.to"
            class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            {{ item.name }}
          </router-link>
          <span 
            v-else
            class="ml-2 text-sm font-medium text-gray-400 dark:text-gray-500"
            aria-current="page"
          >
            {{ item.name }}
          </span>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script>
import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import { computed } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'AppBreadcrumb',
  components: {
    HomeIcon,
    ChevronRightIcon
  },
  setup() {
    const route = useRoute();

    const breadcrumbs = computed(() => {
      const matchedRoutes = route.matched.filter(record => record.meta.breadcrumb);
      
      return matchedRoutes.map((record) => {
        return {
          name: record.meta.breadcrumb,
          to: record.path
        };
      });
    });

    return {
      breadcrumbs
    };
  }
};
</script>
