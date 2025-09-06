<template>
  <div class="company-edit">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ isNew ? $t('companies.create_company') : $t('companies.edit_company') }}
      </h1>
      
      <div class="flex space-x-3">
        <BaseButton
          :to="isNew ? { name: 'companies.list' } : { name: 'companies.view', params: { id: form.id } }"
          variant="outline"
          size="sm"
        >
          {{ $t('common.cancel') }}
        </BaseButton>
        
        <BaseButton
          @click="saveCompany"
          :loading="loading"
          variant="primary"
          size="sm"
        >
          {{ $t('common.save') }}
        </BaseButton>
      </div>
    </div>
    
    <BaseCard>
      <div class="p-6">
        <div class="space-y-6">
          <!-- Basic Information -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
              {{ $t('common.basic_information') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <BaseInput
                v-model="form.name"
                :label="$t('common.name')"
                required
                :error="errors.name"
              />
              
              <BaseInput
                v-model="form.email"
                type="email"
                :label="$t('common.email')"
                :error="errors.email"
              />
              
              <BaseInput
                v-model="form.phone"
                :label="$t('common.phone')"
                :error="errors.phone"
              />
              
              <BaseInput
                v-model="form.website"
                :label="$t('common.website')"
                placeholder="https://example.com"
                :error="errors.website"
              />
              
              <div class="md:col-span-2">
                <BaseTextarea
                  v-model="form.description"
                  :label="$t('common.description')"
                  :error="errors.description"
                  :rows="4"
                />
              </div>
            </div>
          </div>
          
          <!-- Address Information -->
          <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
              {{ $t('common.address_information') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <BaseInput
                v-model="form.address"
                :label="$t('common.address')"
                :error="errors.address"
              />
              
              <BaseInput
                v-model="form.city"
                :label="$t('common.city')"
                :error="errors.city"
              />
              
              <BaseInput
                v-model="form.country"
                :label="$t('common.country')"
                :error="errors.country"
              />
              
              <BaseInput
                v-model="form.postal_code"
                :label="$t('common.postal_code')"
                :error="errors.postal_code"
              />
            </div>
          </div>
          
          <!-- Status -->
          <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
              {{ $t('common.status') }}
            </h3>
            
            <ToogleSwitch
              v-model="form.is_active"
              :label="form.is_active ? $t('common.active') : $t('common.inactive')"
              :description="form.is_active ? $t('companies.active_description') : $t('companies.inactive_description')"
            />
          </div>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCompanyStore } from '@/stores/company';
import BaseCard from '@/components/ui/BaseCard.vue';
import BaseButton from '@/components/ui/Button.vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseTextarea from '@/components/ui/BaseTextarea.vue';
import ToogleSwitch from '@/components/ui/ToggleSwitch.vue';

export default {
  name: 'CompanyEdit',
  
  components: {
    BaseCard,
    BaseButton,
    BaseInput,
    BaseTextarea,
    ToogleSwitch
  },
  
  setup() {
    const route = useRoute();
    const router = useRouter();
    const companyStore = useCompanyStore();
    
    const loading = ref(false);
    const errors = ref({});
    const isNew = computed(() => route.name === 'companies.create');
    
    const form = ref({
      id: null,
      name: '',
      email: '',
      phone: '',
      website: '',
      description: '',
      address: '',
      city: '',
      country: '',
      postal_code: '',
      is_active: true
    });
    
    const fetchCompany = async () => {
      if (isNew.value) return;
      
      try {
        loading.value = true;
        const company = await companyStore.fetchCompanyById(route.params.id);
        form.value = { ...company };
      } catch (error) {
        console.error('Error fetching company:', error);
      } finally {
        loading.value = false;
      }
    };
    
    const saveCompany = async () => {
      loading.value = true;
      errors.value = {};
      
      try {
        if (isNew.value) {
          await companyStore.createCompany(form.value);
        } else {
          await companyStore.updateCompany(form.value.id, form.value);
        }
        
        router.push({ 
          name: isNew.value ? 'companies.list' : 'companies.view', 
          params: { id: form.value.id } 
        });
      } catch (error) {
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors;
        } else {
          console.error('Error saving company:', error);
        }
      } finally {
        loading.value = false;
      }
    };
    
    onMounted(() => {
      if (!isNew.value) {
        fetchCompany();
      }
    });
    
    return {
      form,
      errors,
      loading,
      isNew,
      saveCompany
    };
  }
};
</script>
