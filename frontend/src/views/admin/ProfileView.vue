<template>
  <div>
    <ProfileTabs v-model="activeTab" :tabs="tabList" @update:tab="activeTab = $event" />
    <div v-if="activeTab === 'main'" class="space-y-6">
      <ProfileAvatarSection />
      <ProfileMainSection />
      <ProfilePublicSection />
    </div>
    <div v-else-if="activeTab === 'security'" class="space-y-6">
      <ProfileSecuritySection />
      <Profile2FASection />
      <ProfileSessionsSection />
    </div>
    <div v-else-if="activeTab === 'export'" class="space-y-6">
      <ProfileExportSection />
    </div>
    <!-- TODO: Add badges, integrations, notifications, activity history, etc. -->
  </div>

</template>

<script setup>
import { ref } from 'vue';
import ProfileTabs from '@/components/profile/ProfileTabs.vue';
import ProfileMainSection from '@/components/profile/ProfileMainSection.vue';
import ProfileAvatarSection from '@/components/profile/ProfileAvatarSection.vue';
import ProfileSecuritySection from '@/components/profile/ProfileSecuritySection.vue';
import Profile2FASection from '@/components/profile/Profile2FASection.vue';
import ProfileSessionsSection from '@/components/profile/ProfileSessionsSection.vue';
import ProfileExportSection from '@/components/profile/ProfileExportSection.vue';
import ProfilePublicSection from '@/components/profile/ProfilePublicSection.vue';

const activeTab = ref('main');
const tabList = [
  { key: 'main', label: 'profile.tabs.main' },
  { key: 'security', label: 'profile.tabs.security' },
  { key: 'export', label: 'profile.tabs.export' },
];
</script>