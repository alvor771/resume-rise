<template>
  <StaticPageLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <!-- Introduction -->
        <section id="introduction" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.introduction.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.introduction.content') }}</p>
        </section>

        <!-- What Are Cookies -->
        <section id="what-are-cookies" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.whatAreCookies.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.whatAreCookies.content') }}</p>
        </section>

        <!-- Types of Cookies -->
        <section id="types-of-cookies" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.typesOfCookies.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.typesOfCookies.intro') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.typesOfCookies.necessary.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.typesOfCookies.necessary.content') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.typesOfCookies.preferences.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.typesOfCookies.preferences.content') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.typesOfCookies.analytics.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.typesOfCookies.analytics.content') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.typesOfCookies.advertising.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.typesOfCookies.advertising.content') }}</p>
        </section>

        <!-- Managing Cookies -->
        <section id="managing-cookies" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.managingCookies.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.managingCookies.content') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.managingCookies.browserSettings.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.managingCookies.browserSettings.content') }}</p>
          
          <h3>{{ $t('static.cookiePolicy.sections.managingCookies.thirdPartyCookies.title') }}</h3>
          <p>{{ $t('static.cookiePolicy.sections.managingCookies.thirdPartyCookies.content') }}</p>
        </section>

        <!-- Changes to Policy -->
        <section id="changes" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.changes.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.changes.content') }}</p>
        </section>

        <!-- Contact -->
        <section id="contact" class="mb-12">
          <h2>{{ $t('static.cookiePolicy.sections.contact.title') }}</h2>
          <p>{{ $t('static.cookiePolicy.sections.contact.content') }}</p>
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
  name: 'CookiePolicy',
  components: {
    StaticPageLayout,
    TableOfContents
  },
  setup() {
    const tocItems = [
      { id: 'introduction', text: 'Introduction' },
      { id: 'what-are-cookies', text: 'What Are Cookies' },
      { id: 'types-of-cookies', text: 'Types of Cookies' },
      { id: 'managing-cookies', text: 'Managing Cookies' },
      { id: 'changes', text: 'Changes to This Policy' },
      { id: 'contact', text: 'Contact Us' }
    ];

    return {
      tocItems
    };
  }
});
</script>
