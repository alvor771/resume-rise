<template>
  <div class="vacancy-list">
    <div class="d-flex justify-space-between align-center mb-4">
      <h1>{{ $t('vacancies.title') }}</h1>
      <v-btn
        color="primary"
        :to="{ name: 'VacancyCreate' }"
        prepend-icon="mdi-plus"
      >
        {{ $t('common.add') }}
      </v-btn>
    </div>

    <v-card class="mb-4">
      <v-card-text>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              v-model="filters.search"
              :label="$t('common.search')"
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              density="comfortable"
              hide-details
              clearable
              @update:modelValue="handleSearch"
            />
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.status"
              :items="statusOptions"
              item-title="text"
              item-value="value"
              :label="$t('common.status')"
              variant="outlined"
              density="comfortable"
              hide-details
              clearable
              @update:modelValue="fetchVacancies"
            />
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.published"
              :items="publishOptions"
              item-title="text"
              item-value="value"
              :label="$t('vacancies.fields.published')"
              variant="outlined"
              density="comfortable"
              hide-details
              clearable
              @update:modelValue="fetchVacancies"
            />
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.employment_type"
              :items="employmentTypeOptions"
              item-title="text"
              item-value="value"
              :label="$t('vacancies.fields.employment_type')"
              variant="outlined"
              density="comfortable"
              hide-details
              clearable
              @update:modelValue="fetchVacancies"
            />
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.experience_level"
              :items="experienceLevelOptions"
              item-title="text"
              item-value="value"
              :label="$t('vacancies.fields.experience_level')"
              variant="outlined"
              density="comfortable"
              hide-details
              clearable
              @update:modelValue="fetchVacancies"
            />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card>
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

        <v-alert
          v-if="!loading && vacancies.length === 0"
          type="info"
        >
          {{ $t('vacancies.noVacanciesFound') }}
        </v-alert>

        <v-list v-if="!loading && vacancies.length > 0" class="pa-0">
          <v-list-item
            v-for="vacancy in vacancies"
            :key="vacancy.id"
            :to="{ name: 'VacancyEdit', params: { id: vacancy.id } }"
            class="mb-2"
          >
            <template #prepend>
              <v-avatar
                v-if="vacancy.company?.logo"
                :image="vacancy.company.logo"
                size="56"
                class="me-4"
              />
              <v-avatar
                v-else
                color="primary"
                size="56"
                class="me-4 text-white"
              >
                {{ vacancy.company?.name?.charAt(0)?.toUpperCase() || 'C' }}
              </v-avatar>
            </template>

            <v-list-item-title class="text-h6">
              {{ vacancy.title }}
              <v-chip
                v-if="vacancy.is_published"
                size="small"
                color="success"
                class="ms-2"
              >
                {{ $t('common.published') }}
              </v-chip>
              <v-chip
                v-else
                size="small"
                color="warning"
                class="ms-2"
              >
                {{ $t('common.draft') }}
              </v-chip>
              <v-chip
                size="small"
                :color="vacancy.is_active ? 'success' : 'error'"
                class="ms-2"
              >
                {{ vacancy.is_active ? $t('common.active') : $t('common.inactive') }}
              </v-chip>
            </v-list-item-title>

            <v-list-item-subtitle>
              <div class="d-flex align-center flex-wrap mt-1">
                <div class="d-flex align-center me-4">
                  <v-icon size="small" class="me-1">mdi-domain</v-icon>
                  <span>{{ vacancy.company?.name || $t('common.notSpecified') }}</span>
                </div>

                <div class="d-flex align-center me-4">
                  <v-icon size="small" class="me-1">mdi-account-tie</v-icon>
                  <span>{{ employmentTypeLabel(vacancy.employment_type) }}</span>
                </div>

                <div class="d-flex align-center me-4">
                  <v-icon size="small" class="me-1">mdi-chart-timeline-variant</v-icon>
                  <span>{{ experienceLevelLabel(vacancy.experience_level) }}</span>
                </div>

                <div v-if="vacancy.salary_min || vacancy.salary_max" class="d-flex align-center">
                  <v-icon size="small" class="me-1">mdi-cash</v-icon>
                  <span>{{ formatSalary(vacancy) }}</span>
                </div>
              </div>

              <div class="d-flex align-center mt-1">
                <v-icon size="small" class="me-1">mdi-map-marker</v-icon>
                <span>
                  {{ [vacancy.location, vacancy.is_remote ? $t('vacancies.remote') : null].filter(Boolean).join(' • ') }}
                </span>
              </div>
            </v-list-item-subtitle>

            <template #append>
              <v-menu>
                <template #activator="{ props: menuProps }">
                  <v-btn
                    variant="text"
                    icon="mdi-dots-vertical"
                    v-bind="menuProps"
                    @click.stop
                  />
                </template>

                <v-list>
                  <v-list-item
                    v-if="!vacancy.is_published"
                    :title="$t('vacancies.actions.publish')"
                    prepend-icon="mdi-publish"
                    @click.stop="publishVacancy(vacancy.id)"
                  />
                  <v-list-item
                    v-else
                    :title="$t('vacancies.actions.unpublish')"
                    prepend-icon="mdi-close-circle"
                    @click.stop="unpublishVacancy(vacancy.id)"
                  />
                  <v-list-item
                    :title="$t('common.delete')"
                    prepend-icon="mdi-delete"
                    @click.stop="confirmDelete(vacancy)"
                  />
                </v-list>
              </v-menu>
            </template>
          </v-list-item>
        </v-list>

        <v-pagination
          v-if="pagination.lastPage > 1"
          v-model="pagination.page"
          :length="pagination.lastPage"
          :total-visible="5"
          class="mt-4"
          @update:modelValue="handlePageChange"
        />
      </v-card-text>
    </v-card>

    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h6">
          {{ $t('common.confirmDelete') }}
        </v-card-title>
        <v-card-text>
          {{ $t('vacancies.deleteConfirmation', { title: selectedVacancy?.title }) }}
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="grey"
            variant="text"
            @click="deleteDialog = false"
          >
            {{ $t('common.cancel') }}
          </v-btn>
          <v-btn
            color="error"
            variant="tonal"
            :loading="deleting"
            @click="deleteVacancy(selectedVacancy.id)"
          >
            {{ $t('common.delete') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useVacancyStore } from '@/stores/vacancy';

export default {
  name: 'VacancyList',
  
  data() {
    return {
      statusOptions: [
        { text: this.$t('common.all'), value: 'all' },
        { text: this.$t('common.active'), value: 'active' },
        { text: this.$t('common.inactive'), value: 'inactive' },
      ],
      publishOptions: [
        { text: this.$t('common.all'), value: 'all' },
        { text: this.$t('common.published'), value: 'published' },
        { text: this.$t('common.draft'), value: 'draft' },
      ],
      employmentTypeOptions: [
        { text: this.$t('vacancies.employmentTypes.full_time'), value: 'full_time' },
        { text: this.$t('vacancies.employmentTypes.part_time'), value: 'part_time' },
        { text: this.$t('vacancies.employmentTypes.contract'), value: 'contract' },
        { text: this.$t('vacancies.employmentTypes.temporary'), value: 'temporary' },
        { text: this.$t('vacancies.employmentTypes.internship'), value: 'internship' },
      ],
      experienceLevelOptions: [
        { text: this.$t('vacancies.experienceLevels.intern'), value: 'intern' },
        { text: this.$t('vacancies.experienceLevels.entry'), value: 'entry' },
        { text: this.$t('vacancies.experienceLevels.mid'), value: 'mid' },
        { text: this.$t('vacancies.experienceLevels.senior'), value: 'senior' },
        { text: this.$t('vacancies.experienceLevels.lead'), value: 'lead' },
        { text: this.$t('vacancies.experienceLevels.executive'), value: 'executive' },
      ],
      searchTimeout: null,
      deleteDialog: false,
      deleting: false,
      selectedVacancy: null,
    };
  },

  computed: {
    ...mapState(useVacancyStore, [
      'vacancies',
      'loading',
      'error',
      'pagination',
      'filters',
    ]),
  },

  created() {
    this.fetchVacancies();
  },

  methods: {
    ...mapActions(useVacancyStore, [
      'fetchVacancies',
      'deleteVacancy',
      'publishVacancy',
      'unpublishVacancy',
      'setFilters',
      'setPagination',
    ]),

    employmentTypeLabel(type) {
      if (!type) return this.$t('common.notSpecified');
      const option = this.employmentTypeOptions.find(opt => opt.value === type);
      return option ? option.text : type;
    },

    experienceLevelLabel(level) {
      if (!level) return this.$t('common.notSpecified');
      const option = this.experienceLevelOptions.find(opt => opt.value === level);
      return option ? option.text : level;
    },

    formatSalary(vacancy) {
      if (vacancy.salary_min && vacancy.salary_max) {
        return `${this.$n(vacancy.salary_min)} - ${this.$n(vacancy.salary_max)} ${vacancy.salary_currency || ''}`.trim();
      }
      if (vacancy.salary_min) {
        return `${this.$t('vacancies.from')} ${this.$n(vacancy.salary_min)} ${vacancy.salary_currency || ''}`.trim();
      }
      if (vacancy.salary_max) {
        return `${this.$t('vacancies.upTo')} ${this.$n(vacancy.salary_max)} ${vacancy.salary_currency || ''}`.trim();
      }
      return this.$t('common.negotiable');
    },

    handleSearch() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      
      this.searchTimeout = setTimeout(() => {
        this.setFilters({ search: this.filters.search });
        this.fetchVacancies();
      }, 500);
    },

    handlePageChange(page) {
      this.setPagination({ page });
      this.fetchVacancies();
    },

    confirmDelete(vacancy) {
      this.selectedVacancy = vacancy;
      this.deleteDialog = true;
    },

    async deleteVacancyHandler(id) {
      this.deleting = true;
      try {
        await this.deleteVacancy(id);
        this.deleteDialog = false;
      } finally {
        this.deleting = false;
      }
    },
  },
};
</script>

<style scoped>
.vacancy-list {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.v-list-item {
  border: 1px solid rgba(0, 0, 0, 0.12);
  border-radius: 4px;
  transition: all 0.3s ease;
}

.v-list-item:hover {
  background-color: rgba(0, 0, 0, 0.02);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
