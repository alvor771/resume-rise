<template>
  <StaticPageLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <!-- Introduction -->
        <section id="introduction" class="mb-12">
          <h2>{{ $t('static.documentation.sections.introduction.title') }}</h2>
          <p>{{ $t('static.documentation.sections.introduction.content') }}</p>
        </section>

        <!-- Getting Started -->
        <section id="getting-started" class="mb-12">
          <h2>{{ $t('static.documentation.sections.gettingStarted.title') }}</h2>
          <p>{{ $t('static.documentation.sections.gettingStarted.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.gettingStarted.accountSetup.title') }}</h3>
          <p>{{ $t('static.documentation.sections.gettingStarted.accountSetup.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.gettingStarted.dashboardOverview.title') }}</h3>
          <p>{{ $t('static.documentation.sections.gettingStarted.dashboardOverview.content') }}</p>
        </section>

        <!-- Resume Builder -->
        <section id="resume-builder" class="mb-12">
          <h2>{{ $t('static.documentation.sections.resumeBuilder.title') }}</h2>
          <p>{{ $t('static.documentation.sections.resumeBuilder.intro') }}</p>
          
          <h3>{{ $t('static.documentation.sections.resumeBuilder.creatingResume.title') }}</h3>
          <p>{{ $t('static.documentation.sections.resumeBuilder.creatingResume.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.resumeBuilder.templates.title') }}</h3>
          <p>{{ $t('static.documentation.sections.resumeBuilder.templates.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.resumeBuilder.exporting.title') }}</h3>
          <p>{{ $t('static.documentation.sections.resumeBuilder.exporting.content') }}</p>
        </section>

        <!-- Account Management -->
        <section id="account-management" class="mb-12">
          <h2>{{ $t('static.documentation.sections.accountManagement.title') }}</h2>
          
          <h3>{{ $t('static.documentation.sections.accountManagement.profileSettings.title') }}</h3>
          <p>{{ $t('static.documentation.sections.accountManagement.profileSettings.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.accountManagement.subscription.title') }}</h3>
          <p>{{ $t('static.documentation.sections.accountManagement.subscription.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.accountManagement.security.title') }}</h3>
          <p>{{ $t('static.documentation.sections.accountManagement.security.content') }}</p>
        </section>

        <!-- API Integration -->
        <section id="api-integration" class="mb-12">
          <h2>{{ $t('static.documentation.sections.apiIntegration.title') }}</h2>
          <p>{{ $t('static.documentation.sections.apiIntegration.intro') }}</p>
          
          <h3>{{ $t('static.documentation.sections.apiIntegration.authentication.title') }}</h3>
          <p>{{ $t('static.documentation.sections.apiIntegration.authentication.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.apiIntegration.endpoints.title') }}</h3>
          <p>{{ $t('static.documentation.sections.apiIntegration.endpoints.content') }}</p>
          
          <h3>{{ $t('static.documentation.sections.apiIntegration.rateLimiting.title') }}</h3>
          <p>{{ $t('static.documentation.sections.apiIntegration.rateLimiting.content') }}</p>
        </section>

        <!-- FAQ -->
        <section id="faq" class="mb-12">
          <h2>{{ $t('static.documentation.sections.faq.title') }}</h2>
          
          <div v-for="(item, index) in $t('static.documentation.sections.faq.items')" :key="index" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ item.question }}</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ item.answer }}</p>
          </div>
        </section>

        <!-- Support -->
        <section id="support" class="mb-12">
          <h2>{{ $t('static.documentation.sections.support.title') }}</h2>
          <p>{{ $t('static.documentation.sections.support.content') }}</p>
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
  name: 'DocumentationView',
  components: {
    StaticPageLayout,
    TableOfContents
  },
  setup() {
    const tocItems = [
      { id: 'introduction', text: 'Introduction' },
      { id: 'getting-started', text: 'Getting Started' },
      { id: 'resume-builder', text: 'Resume Builder' },
      { id: 'account-management', text: 'Account Management' },
      { id: 'api-integration', text: 'API Integration' },
      { id: 'faq', text: 'FAQ' },
      { id: 'support', text: 'Support' }
    ];

    return {
      tocItems
    };
  }
});
</script>
