<template>
  <div class="company-form">
    <v-breadcrumbs :items="breadcrumbs" class="px-0 py-4">
      <template #divider>
        <v-icon>mdi-chevron-right</v-icon>
      </template>
    </v-breadcrumbs>

    <v-card>
      <v-card-title class="text-h5">
        {{ isEdit ? $t('companies.editCompany') : $t('companies.createCompany') }}
      </v-card-title>

      <v-form @submit.prevent="submit">
        <v-card-text>
          <v-progress-linear
            v-if="loading"
            indeterminate
            color="primary"
            class="mb-4"
          />

          <v-alert
            v-if="error"
            type="error"
            class="mb-4"
          >
            {{ error }}
          </v-alert>

          <v-row>
            <v-col cols="12" md="8">
              <v-text-field
                v-model="formData.name"
                :label="$t('companies.fields.name')"
                :rules="[v => !!v || $t('validation.required')]"
                :error-messages="errors.name"
                variant="outlined"
                density="comfortable"
                class="mb-4"
                required
              />

              <v-textarea
                v-model="formData.description"
                :label="$t('companies.fields.description')"
                :error-messages="errors.description"
                variant="outlined"
                density="comfortable"
                rows="3"
                auto-grow
                class="mb-4"
              />

              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="formData.email"
                    :label="$t('companies.fields.email')"
                    type="email"
                    :error-messages="errors.email"
                    variant="outlined"
                    density="comfortable"
                    class="mb-4"
                  />
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="formData.phone"
                    :label="$t('companies.fields.phone')"
                    :error-messages="errors.phone"
                    variant="outlined"
                    density="comfortable"
                    class="mb-4"
                  />
                </v-col>
              </v-row>

              <v-text-field
                v-model="formData.website"
                :label="$t('companies.fields.website')"
                :error-messages="errors.website"
                variant="outlined"
                density="comfortable"
                class="mb-4"
                prepend-inner-icon="mdi-web"
              />

              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="formData.city"
                    :label="$t('companies.fields.city')"
                    :error-messages="errors.city"
                    variant="outlined"
                    density="comfortable"
                    class="mb-4"
                  />
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="formData.country"
                    :label="$t('companies.fields.country')"
                    :error-messages="errors.country"
                    variant="outlined"
                    density="comfortable"
                    class="mb-4"
                  />
                </v-col>
              </v-row>

              <v-checkbox
                v-model="formData.is_active"
                :label="$t('companies.fields.is_active')"
                :error-messages="errors.is_active"
                hide-details
                class="mt-0"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-card variant="outlined" class="mb-4">
                <v-card-text>
                  <div class="d-flex justify-center mb-4">
                    <v-avatar
                      v-if="logoPreview"
                      :image="logoPreview"
                      size="200"
                      class="mb-2"
                    />
                    <v-avatar
                      v-else
                      color="grey-lighten-2"
                      size="200"
                      class="mb-2"
                    >
                      <v-icon size="64" color="grey">mdi-domain</v-icon>
                    </v-avatar>
                  </div>

                  <v-file-input
                    v-model="logoFile"
                    :label="$t('companies.fields.logo')"
                    :error-messages="errors.logo"
                    accept="image/*"
                    prepend-icon="mdi-camera"
                    variant="outlined"
                    density="comfortable"
                    @update:model-value="handleLogoSelect"
                  />
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider />

        <v-card-actions class="px-4 py-4">
          <v-spacer />
          <v-btn
            :to="{ name: 'companies.index' }"
            variant="text"
            class="me-2"
          >
            {{ $t('common.cancel') }}
          </v-btn>
          <v-btn
            type="submit"
            color="primary"
            :loading="submitting"
            :disabled="!formValid"
          >
            {{ isEdit ? $t('common.update') : $t('common.create') }}
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCompanyStore } from '@/stores/company';

export default {
  name: 'CompanyForm',
  
  props: {
    id: {
      type: [String, Number],
      default: null,
    },
  },

  data() {
    return {
      formData: {
        name: '',
        description: '',
        email: '',
        phone: '',
        website: '',
        city: '',
        country: '',
        is_active: true,
        logo: null,
      },
      logoFile: null,
      logoPreview: null,
      submitting: false,
      errors: {},
    };
  },

  computed: {
    ...mapState(useCompanyStore, [
      'loading',
      'error',
      'currentCompany',
    ]),

    isEdit() {
      return !!this.id;
    },

    formValid() {
      return !!this.formData.name;
    },

    breadcrumbs() {
      return [
        {
          title: this.$t('common.home'),
          to: { name: 'home' },
          disabled: false,
        },
        {
          title: this.$t('companies.title'),
          to: { name: 'companies.index' },
          disabled: false,
        },
        {
          title: this.isEdit ? this.$t('common.edit') : this.$t('common.create'),
          disabled: true,
        },
      ];
    },
  },

  watch: {
    currentCompany: {
      immediate: true,
      handler(company) {
        if (company && this.isEdit) {
          this.setFormData(company);
        }
      },
    },
  },

  created() {
    if (this.isEdit) {
      this.fetchCompany(this.id);
    }
  },

  methods: {
    ...mapActions(useCompanyStore, [
      'fetchCompany',
      'createCompany',
      'updateCompany',
    ]),

    setFormData(company) {
      this.formData = {
        name: company.name || '',
        description: company.description || '',
        email: company.email || '',
        phone: company.phone || '',
        website: company.website || '',
        city: company.city || '',
        country: company.country || '',
        is_active: company.is_active !== undefined ? company.is_active : true,
        logo: company.logo || null,
      };
      this.logoPreview = company.logo || null;
    },

    handleLogoSelect(file) {
      if (file) {
        this.logoPreview = URL.createObjectURL(file);
        this.formData.logo = file;
      } else {
        this.logoPreview = null;
        this.formData.logo = null;
      }
    },

    async submit() {
      if (!this.formValid) return;

      this.submitting = true;
      this.errors = {};

      try {
        const formData = new FormData();
        
        // Append all form data to FormData
        Object.entries(this.formData).forEach(([key, value]) => {
          if (value !== null && value !== undefined) {
            formData.append(key, value);
          }
        });

        if (this.isEdit) {
          // For updates, we need to use POST with _method=PUT for file uploads
          formData.append('_method', 'PUT');
          await this.updateCompany({ id: this.id, ...Object.fromEntries(formData) });
        } else {
          await this.createCompany(formData);
        }

        this.$router.push({ name: 'companies.index' });
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      } finally {
        this.submitting = false;
      }
    },
  },
};
</script>

<style scoped>
.company-form {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}
</style>
