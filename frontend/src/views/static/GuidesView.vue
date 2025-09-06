<template>
  <StaticPageLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <!-- Introduction -->
        <section id="introduction" class="mb-12">
          <h2>{{ $t('static.guides.sections.introduction.title') }}</h2>
          <p>{{ $t('static.guides.sections.introduction.content') }}</p>
        </section>

        <!-- Getting Started Guides -->
        <section id="getting-started" class="mb-12">
          <h2>{{ $t('static.guides.sections.gettingStarted.title') }}</h2>
          <p>{{ $t('static.guides.sections.gettingStarted.intro') }}</p>
          
          <div class="grid md:grid-cols-2 gap-6 mt-6">
            <div 
              v-for="(guide, index) in $t('static.guides.sections.gettingStarted.guides')" 
              :key="index" 
              class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
            >
              <div class="flex items-center mb-4">
                <div class="p-2 rounded-md bg-indigo-100 dark:bg-indigo-900 mr-4">
                  <component :is="guide.icon" class="w-6 h-6 text-indigo-600 dark:text-indigo-300" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ guide.title }}</h3>
              </div>
              <p class="text-gray-600 dark:text-gray-300 mb-4">{{ guide.description }}</p>
              <router-link 
                :to="guide.link" 
                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium"
              >
                {{ $t('static.guides.readGuide') }}
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </router-link>
            </div>
          </div>
        </section>

        <!-- Video Tutorials -->
        <section id="video-tutorials" class="mb-12">
          <h2>{{ $t('static.guides.sections.videoTutorials.title') }}</h2>
          <p class="mb-6">{{ $t('static.guides.sections.videoTutorials.intro') }}</p>
          
          <div class="grid md:grid-cols-2 gap-6">
            <div 
              v-for="(video, index) in $t('static.guides.sections.videoTutorials.videos')" 
              :key="index" 
              class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden"
            >
              <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <div class="text-center p-4">
                  <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $t('static.guides.videoPlaceholder') }}</p>
                </div>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ video.title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ video.duration }} • {{ video.level }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Tips & Tricks -->
        <section id="tips-tricks" class="mb-12">
          <h2>{{ $t('static.guides.sections.tipsTricks.title') }}</h2>
          <p class="mb-6">{{ $t('static.guides.sections.tipsTricks.intro') }}</p>
          
          <div class="space-y-4">
            <div 
              v-for="(tip, index) in $t('static.guides.sections.tipsTricks.tips')" 
              :key="index" 
              class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700"
            >
              <div class="flex">
                <div class="flex-shrink-0 mt-0.5">
                  <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                    <svg class="w-3 h-3 text-indigo-600 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ tip.title }}</h3>
                  <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ tip.content }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Community Resources -->
        <section id="community" class="mb-12">
          <h2>{{ $t('static.guides.sections.community.title') }}</h2>
          <p class="mb-6">{{ $t('static.guides.sections.community.intro') }}</p>
          
          <div class="grid md:grid-cols-2 gap-6">
            <a 
              v-for="(resource, index) in $t('static.guides.sections.community.resources')" 
              :key="index" 
              :href="resource.link" 
              target="_blank" 
              rel="noopener noreferrer"
              class="group border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors"
            >
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                  <div class="w-10 h-10 rounded-md bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                    <component :is="resource.icon" class="w-5 h-5 text-indigo-600 dark:text-indigo-300" />
                  </div>
                </div>
                <div class="ml-4">
                  <h3 class="text-base font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ resource.title }}</h3>
                  <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ resource.description }}</p>
                </div>
              </div>
            </a>
          </div>
        </section>
      </div>

      <!-- Table of Contents -->
      <div class="hidden lg:block">
        <TableOfContents :items="tocItems" />
      </div>
    </div>
  </StaticPageLayout>
</template>

<script>
import { defineComponent } from 'vue';
import StaticPageLayout from '@/layouts/StaticPageLayout.vue';
import TableOfContents from '@/components/static/TableOfContents.vue';

export default defineComponent({
  name: 'GuidesView',
  components: {
    StaticPageLayout,
    TableOfContents
  },
  setup() {
    const tocItems = [
      { id: 'introduction', text: 'Introduction' },
      { id: 'getting-started', text: 'Getting Started' },
      { id: 'video-tutorials', text: 'Video Tutorials' },
      { id: 'tips-tricks', text: 'Tips & Tricks' },
      { id: 'community', text: 'Community Resources' }
    ];

    return {
      tocItems
    };
  }
});
</script>
