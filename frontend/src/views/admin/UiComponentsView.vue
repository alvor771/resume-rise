<template>
  <div class="p-4 space-y-10">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">UI Components</h1>
      <router-link to="/admin/dashboard" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">Back to Dashboard</router-link>
    </div>

    <!-- Forms -->
    <section>
      <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Forms</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseInput" subtitle="Standard text input" />
          <BaseInput v-model="form.name" label="Full Name" placeholder="Your name" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Value: {{ form.name }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseTextarea" subtitle="Multi-line input" />
          <BaseTextarea v-model="form.bio" placeholder="Short bio" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Length: {{ form.bio.length }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseSelect" subtitle="Select input" />
          <BaseSelect v-model="form.country" :options="countries" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Selected: {{ form.country }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseCheckbox" subtitle="Checkbox" />
          <BaseCheckbox v-model="form.accepted">Accept Terms</BaseCheckbox>
          <div class="text-xs text-gray-500 dark:text-gray-400">Accepted: {{ form.accepted }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseRadio" subtitle="Radio group" />
          <div class="space-y-2">
            <BaseRadio v-model="form.plan" value="free">Free</BaseRadio>
            <BaseRadio v-model="form.plan" value="pro">Pro</BaseRadio>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Plan: {{ form.plan }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="ChipInput" subtitle="Tags input" />
          <ChipInput v-model="form.tags" placeholder="Add tags" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Tags: {{ form.tags.join(', ') }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="DatePicker" subtitle="Pick a date" />
          <DatePicker v-model="form.date" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Date: {{ form.date }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="PhotoUpload" subtitle="Upload a photo" />
          <PhotoUpload v-model="form.photo" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Has Photo: {{ !!form.photo }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="Button" subtitle="Action button" />
          <div class="flex gap-2">
            <Button @click="randomize()">Primary</Button>
            <Button variant="secondary">Secondary</Button>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="SearchInput" subtitle="Input with search icon" />
          <SearchInput v-model="form.search" label="Search" placeholder="Search..." />
          <div class="text-xs text-gray-500 dark:text-gray-400">Query: {{ form.search }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="PasswordInput" subtitle="Password with toggle and strength" />
          <PasswordInput v-model="form.password" label="Password" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Length: {{ (form.password || '').length }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="ToggleSwitch" subtitle="Boolean switcher" />
          <ToggleSwitch v-model="form.enabled" label="Enabled" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Enabled: {{ form.enabled }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="PhoneInput" subtitle="Formatted phone number input" />
      
          <PhoneInput
            v-model="phoneNumber"
            label="Номер телефона"
            required
            format="(48) ###-###-###"
            :prefix-icon="PhoneIcon"
            @update:model-value="handlePhoneUpdate"
          />
          <div class="text-xs text-gray-500 dark:text-gray-400">Value: {{ form.phone || 'Empty' }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="Input with icon" subtitle="Labeled + prefix icon" />
          <BaseInput v-model="form.email" label="Email" placeholder="you@example.com" :prefix-icon="IconMail" />
          <div class="text-xs text-gray-500 dark:text-gray-400">Email: {{ form.email }}</div>
        </div>
      </div>
    </section>

    <!-- Icons -->
    <section>
      <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Icons</h2>
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
          <div v-for="icon in icons" :key="icon.name" class="flex flex-col items-center p-3 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <component :is="icon.component" class="w-6 h-6 text-gray-700 dark:text-gray-200" />
            <div class="mt-2 text-xs text-center text-gray-600 dark:text-gray-400 break-all">{{ icon.name }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Others -->
    <section>
      <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Others</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="Breadcrumbs (new)" subtitle="Page navigation" />
          <Breadcrumbs :items="[
            { label: 'Home', to: '/' },
            { label: 'Admin', to: '/admin' },
            { label: 'UI Components' }
          ]" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="ThemeToggle" subtitle="Light/Dark" />
          <ThemeToggle />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="GradientBorder" subtitle="Decorative wrapper" />
          <GradientBorder>
            <div class="p-4 text-sm text-gray-800 dark:text-gray-200">Gradient bordered content</div>
          </GradientBorder>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="LanguageSelector" subtitle="Switch language" />
          <LanguageSelector />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="ConfirmationModal" subtitle="Ask for confirmation" />
          <ConfirmationModal v-model="modalOpen" title="Confirm" description="Are you sure?" @confirm="modalOpen=false">
            <template #trigger>
              <Button @click="modalOpen=true">Open modal</Button>
            </template>
          </ConfirmationModal>
        </div>
      </div>
    </section>

    <!-- New Components Showcase -->
    <section>
      <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">New UI Components</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
          <SectionHeader title="BaseTabs" subtitle="Switch between panels" />
          <BaseTabs v-model="activeTab" :tabs="tabs" />
          <div class="mt-3">
            <template v-if="activeTab === 'overview'">
              <p class="text-sm text-gray-600 dark:text-gray-300">Overview content...</p>
            </template>
            <template v-else-if="activeTab === 'details'">
              <p class="text-sm text-gray-600 dark:text-gray-300">Details content...</p>
            </template>
            <template v-else>
              <p class="text-sm text-gray-600 dark:text-gray-300">Settings content...</p>
            </template>
          </div>
        </div>

        <!-- Modal & Dropdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-4">
          <SectionHeader title="BaseModal & BaseDropdown" subtitle="Overlay and menus" />
          <div class="flex items-center gap-3">
            <Button @click="demoModal = true">Open Modal</Button>
            <BaseDropdown>
              <template #trigger>
                <Button variant="outline-secondary">Open Menu</Button>
              </template>
              <button class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Item 1</button>
              <button class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Item 2</button>
            </BaseDropdown>
          </div>
          <BaseModal v-model="demoModal" title="Demo Modal">
            <p class="text-sm text-gray-700 dark:text-gray-300">This is a simple modal content.</p>
            <template #footer>
              <div class="flex justify-end gap-2">
                <Button variant="outline-secondary" @click="demoModal=false">Close</Button>
                <Button @click="demoModal=false">Confirm</Button>
              </div>
            </template>
          </BaseModal>
        </div>

        <!-- Alerts & Skeleton -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
          <SectionHeader title="BaseAlert & Skeleton" subtitle="Feedback and loading" />
          <BaseAlert variant="info" title="Info">Informational message</BaseAlert>
          <BaseAlert variant="success" title="Success">Everything went well</BaseAlert>
          <BaseAlert variant="warning" title="Warning">Be careful with this action</BaseAlert>
          <BaseAlert variant="danger" title="Error" :closable="true" @close="onCloseAlert">Something went wrong</BaseAlert>
          <div class="mt-2">
            <Skeleton variant="lines" :lines="3" :block="true" />
          </div>
        </div>

        <!-- Stats & Cards -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
          <SectionHeader title="StatsCard & BaseCard" subtitle="Metrics cards" />
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <StatsCard label="Users" :value="1520" :delta="12" />
            <StatsCard label="Revenue" value="$12.3k" :delta="-3" />
          </div>
          <div class="mt-4">
            <BaseCard title="Card title" subtitle="Optional subtitle">
              <p class="text-sm text-gray-600 dark:text-gray-300">Card content goes here.</p>
              <template #actions><Button size="sm">Action</Button></template>
              <template #footer><div class="text-xs text-gray-500 dark:text-gray-400">Footer area</div></template>
            </BaseCard>
          </div>
        </div>

        <!-- DataTable & SimpleList -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
          <SectionHeader title="DataTable & SimpleList" subtitle="Data display" />
          <DataTable
            :items="tableItems"
            :columns="tableColumns"
            v-model:sortBy="sortBy"
            v-model:sortDir="sortDir"
            v-model:page="page"
            :page-size="5"
          />
          <div class="mt-4">
            <SimpleList :items="listItems" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Form components
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseTextarea from '@/components/ui/BaseTextarea.vue';
import BaseSelect from '@/components/ui/BaseSelect.vue';
import BaseCheckbox from '@/components/ui/BaseCheckbox.vue';
import BaseRadio from '@/components/ui/BaseRadio.vue';
import ChipInput from '@/components/ui/ChipInput.vue';
import DatePicker from '@/components/ui/DatePicker.vue';
import PhotoUpload from '@/components/ui/PhotoUpload.vue';
import Button from '@/components/ui/Button.vue';
import SearchInput from '@/components/ui/SearchInput.vue';
import PasswordInput from '@/components/ui/PasswordInput.vue';
import ToggleSwitch from '@/components/ui/ToggleSwitch.vue';
import PhoneInput from '@/components/ui/PhoneInput.vue';
import IconMail from '@/components/ui/icons/IconMail.vue';

// Misc components
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import ConfirmationModal from '@/components/ui/ConfirmationModal.vue';
import LanguageSelector from '@/components/ui/LanguageSelector.vue';
import SectionHeader from '@/components/ui/SectionHeader.vue';
import ThemeToggle from '@/components/ui/ThemeToggle.vue';
import GradientBorder from '@/components/ui/GradientBorder.vue';

// Newly added UI components
import BaseTabs from '@/components/ui/BaseTabs.vue';
import BaseModal from '@/components/ui/BaseModal.vue';
import BaseDropdown from '@/components/ui/BaseDropdown.vue';
import BaseAlert from '@/components/ui/BaseAlert.vue';
import Skeleton from '@/components/ui/Skeleton.vue';
import StatsCard from '@/components/ui/StatsCard.vue';
import BaseCard from '@/components/ui/BaseCard.vue';
import DataTable from '@/components/ui/DataTable.vue';
import SimpleList from '@/components/ui/SimpleList.vue';

// Load all icons dynamically (glob does not support aliases like @)
let iconModules = {};
try {
  iconModules = import.meta.glob('../../components/ui/icons/*.vue', { eager: true });
} catch (e) {
  // Fallback: empty set if environment doesn't support import.meta.glob
  iconModules = {};
}
const icons = computed(() => {
  return Object.entries(iconModules)
    .map(([path, mod]) => {
      const name = path.split('/').pop().replace('.vue', '');
      return { name, component: mod.default };
    })
    .sort((a, b) => a.name.localeCompare(b.name));
});

const form = ref({
  name: '',
  bio: '',
  country: 'US',
  accepted: false,
  plan: 'free',
  tags: [],
  date: '',
  photo: null,
  search: '',
  password: '',
  enabled: true,
  email: '',
  phone: '',
});

const countries = [
  { value: 'US', label: 'United States' },
  { value: 'DE', label: 'Germany' },
  { value: 'PL', label: 'Poland' },
  { value: 'UA', label: 'Ukraine' },
  { value: 'KZ', label: 'Kazakhstan' },
];

const modalOpen = ref(false);
const demoModal = ref(false);

// Tabs demo
const activeTab = ref('overview');
const tabs = [
  { key: 'overview', label: 'Overview' },
  { key: 'details', label: 'Details' },
  { key: 'settings', label: 'Settings' }
];

// Table demo
const tableColumns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'role', label: 'Role', sortable: true },
];
const tableItems = [
  { id: 1, name: 'Alice', email: 'alice@example.com', role: 'Admin' },
  { id: 2, name: 'Bob', email: 'bob@example.com', role: 'User' },
  { id: 3, name: 'Carol', email: 'carol@example.com', role: 'User' },
  { id: 4, name: 'Dave', email: 'dave@example.com', role: 'Editor' },
  { id: 5, name: 'Eve', email: 'eve@example.com', role: 'User' },
  { id: 6, name: 'Frank', email: 'frank@example.com', role: 'Admin' },
];
const sortBy = ref('name');
const sortDir = ref('asc');
const page = ref(1);

// List demo
const listItems = [
  { id: 'a', title: 'Item A', subtitle: 'First item' },
  { id: 'b', title: 'Item B', subtitle: 'Second item' },
  { id: 'c', title: 'Item C', subtitle: 'Third item' },
];

function randomize() {
  form.value.name = 'John Doe';
  form.value.bio = 'Lorem ipsum dolor sit amet';
}

function onCloseAlert() {}
</script>

<style scoped>
</style>
