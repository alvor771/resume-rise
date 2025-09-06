<template>
  <StaticPageLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <!-- Introduction -->
        <section id="introduction" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.introduction.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.introduction.content') }}</p>
        </section>

        <!-- Accounts -->
        <section id="accounts" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.accounts.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.accounts.content') }}</p>
          
          <h3>{{ $t('static.termsOfService.sections.accounts.accountCreation.title') }}</h3>
          <p>{{ $t('static.termsOfService.sections.accounts.accountCreation.content') }}</p>
          
          <h3>{{ $t('static.termsOfService.sections.accounts.accountResponsibilities.title') }}</h3>
          <p>{{ $t('static.termsOfService.sections.accounts.accountResponsibilities.content') }}</p>
        </section>

        <!-- Content -->
        <section id="content" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.content.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.content.intro') }}</p>
          
          <h3>{{ $t('static.termsOfService.sections.content.userContent.title') }}</h3>
          <p>{{ $t('static.termsOfService.sections.content.userContent.content') }}</p>
          
          <h3>{{ $t('static.termsOfService.sections.content.prohibitedContent.title') }}</h3>
          <ul>
            <li v-for="(item, index) in $t('static.termsOfService.sections.content.prohibitedContent.items')" :key="index">
              {{ item }}
            </li>
          </ul>
        </section>

        <!-- Termination -->
        <section id="termination" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.termination.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.termination.content') }}</p>
        </section>

        <!-- Changes -->
        <section id="changes" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.changes.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.changes.content') }}</p>
        </section>

        <!-- Contact -->
        <section id="contact" class="mb-12">
          <h2>{{ $t('static.termsOfService.sections.contact.title') }}</h2>
          <p>{{ $t('static.termsOfService.sections.contact.content') }}</p>
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
  name: 'TermsOfService',
  components: {
    StaticPageLayout,
    TableOfContents
  },
  setup() {
    const tocItems = [
      { id: 'introduction', text: 'Introduction' },
      { id: 'accounts', text: 'Accounts' },
      { id: 'content', text: 'Content' },
      { id: 'termination', text: 'Termination' },
      { id: 'changes', text: 'Changes' },
      { id: 'contact', text: 'Contact Us' }
    ];

    return {
      tocItems
    };
  }
});
</script>
