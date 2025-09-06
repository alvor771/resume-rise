<template>
  <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <!-- Google Maps API Loader (add your API key in production) -->
    <script 
      v-if="!window.google" 
      src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"
      @load="initMap"
      async
      defer
    >
    </script>
    <!-- Interview Details Modal -->
    <TransitionRoot as="template" :show="isModalOpen">
      <Dialog as="div" class="relative z-10" @close="closeModal">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enter-to="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 translate-y-0 sm:scale-100"
              leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                <div>
                  <div class="mt-3 text-center sm:mt-5">
                    <div class="flex justify-between items-start">
                      <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ selectedInterview?.company || $t('user.applications.interview_details') }}
                      </DialogTitle>
                      <button
                        type="button"
                        class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none"
                        @click="closeModal"
                      >
                        <span class="sr-only">{{ $t('common.close') }}</span>
                        <XMarkIcon class="h-6 w-6" />
                      </button>
                    </div>
                    
                    <div class="mt-4 text-left space-y-4">
                      <!-- Position -->
                      <div v-if="selectedInterview?.position">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.position') }}:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                          {{ selectedInterview.position }}
                          <a 
                            v-if="selectedInterview.jobLink" 
                            :href="selectedInterview.jobLink" 
                            target="_blank" 
                            class="ml-2 text-indigo-600 dark:text-indigo-400 hover:underline text-xs"
                          >
                            ({{ $t('user.applications.view_job_posting') }})
                          </a>
                        </p>
                      </div>
                      
                      <!-- Interview Type -->
                      <div v-if="selectedInterview?.interviewType">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.interview_type') }}:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                          {{ selectedInterview.interviewType }}
                        </p>
                      </div>
                      
                      <!-- Interview Date & Time -->
                      <div v-if="selectedInterview?.interviewDate">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.interview_date') }}:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                          {{ formatDateTime(selectedInterview.interviewDate) }}
                        </p>
                      </div>
                      
                      <!-- Location with Map -->
                      <div v-if="selectedInterview?.location">
                        <div class="flex justify-between items-center">
                          <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.location') }}:</h4>
                          <a 
                            v-if="isValidHttpUrl(selectedInterview.location)" 
                            :href="selectedInterview.location" 
                            target="_blank" 
                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center"
                          >
                            <MapPinIcon class="h-3 w-3 mr-1" />
                            {{ $t('user.applications.open_in_maps') }}
                          </a>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                          {{ selectedInterview.location }}
                        </p>
                        <div class="mt-2 h-48 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                          <div 
                            ref="mapContainer" 
                            class="h-full w-full"
                            :class="{ 'flex items-center justify-center': !isMapLoaded }"
                          >
                            <div v-if="!isMapLoaded" class="text-center p-4">
                              <div class="animate-pulse text-gray-500 dark:text-gray-400">
                                <MapPinIcon class="mx-auto h-8 w-8 mb-2" />
                                <p class="text-sm">{{ $t('user.applications.loading_map') }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Contacts -->
                      <div v-if="selectedInterview?.interviewer || selectedInterview?.contactEmail || selectedInterview?.contactPhone">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.contacts') }}:</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                          <li v-if="selectedInterview.interviewer" class="flex items-center">
                            <UserIcon class="h-4 w-4 mr-2 text-gray-400" />
                            {{ selectedInterview.interviewer }}
                          </li>
                          <li v-if="selectedInterview.contactEmail" class="flex items-center">
                            <EnvelopeIcon class="h-4 w-4 mr-2 text-gray-400" />
                            <a :href="'mailto:' + selectedInterview.contactEmail" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                              {{ selectedInterview.contactEmail }}
                            </a>
                          </li>
                          <li v-if="selectedInterview.contactPhone" class="flex items-center">
                            <PhoneIcon class="h-4 w-4 mr-2 text-gray-400" />
                            <a :href="'tel:' + selectedInterview.contactPhone.replace(/[^0-9+]/g, '')" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                              {{ selectedInterview.contactPhone }}
                            </a>
                          </li>
                        </ul>
                      </div>
                      
                      <!-- Notes -->
                      <div v-if="selectedInterview?.notes">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $t('user.applications.notes') }}:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ selectedInterview.notes }}</p>
                      </div>
                      
                      <!-- Applied Date -->
                      <div v-if="selectedInterview?.appliedDate" class="text-xs text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 pt-2">
                        {{ $t('user.applications.applied_on') }}: {{ formatDate(selectedInterview.appliedDate) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 sm:mt-6">
                  <button
                    type="button"
                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm"
                    @click="closeModal"
                  >
                    {{ $t('common.close') }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Calendar Header -->
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">
        {{ $t('user.applications.interview_calendar') }}
      </h3>
      <div class="flex space-x-2">
        <button
          @click="changeView('prev')"
          class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
          :title="$t('common.previous')"
        >
          <ChevronLeftIcon class="h-5 w-5" />
        </button>
        <button
          @click="changeView('today')"
          class="px-3 py-1 text-sm rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600"
        >
          {{ $t('common.today') }}
        </button>
        <button
          @click="changeView('next')"
          class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
          :title="$t('common.next')"
        >
          <ChevronRightIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
    
    <FullCalendar
      ref="calendar"
      class="calendar"
      :options="calendarOptions"
    >
      <template #eventContent="arg">
        <div class="p-1 overflow-hidden">
          <div class="text-xs font-medium truncate">{{ arg.event.title }}</div>
          <div class="text-2xs opacity-75">
            {{ formatTime(arg.event.start) }}
            <template v-if="arg.event.extendedProps.company">
              • {{ arg.event.extendedProps.company }}
            </template>
          </div>
        </div>
      </template>
    </FullCalendar>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { format, parseISO } from 'date-fns';
import { 
  Dialog, 
  DialogPanel, 
  DialogTitle, 
  TransitionChild, 
  TransitionRoot 
} from '@headlessui/vue';
import { 
  XMarkIcon,
  UserIcon,
  EnvelopeIcon,
  PhoneIcon,
  MapPinIcon
} from '@heroicons/vue/24/outline';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

export default {
  name: 'InterviewCalendar',
  components: {
    FullCalendar,
    ChevronLeftIcon,
    ChevronRightIcon,
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    XMarkIcon,
    UserIcon,
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon
  },
  props: {
    interviews: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  setup(props) {
    const calendar = ref(null);
    const isModalOpen = ref(false);
    const selectedInterview = ref(null);
    const mapContainer = ref(null);
    const map = ref(null);
    const isMapLoaded = ref(false);
    let mapInstance = null;
    
    const calendarOptions = {
      plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
      initialView: 'dayGridMonth',
      firstDay: 1, // Start the week on Monday (0 = Sunday, 1 = Monday, etc.)
      headerToolbar: false,
      height: 'auto',
      dayMaxEvents: 3,
      events: props.interviews.map(interview => ({
        id: interview.id,
        title: interview.position,
        start: interview.interviewDate,
        extendedProps: {
          company: interview.company,
          status: interview.status,
          notes: interview.notes || ''
        },
        backgroundColor: getEventColor(interview.status),
        borderColor: getEventColor(interview.status),
        textColor: '#fff',
        classNames: ['cursor-pointer hover:opacity-90']
      })),
      eventClick: (info) => {
        selectedInterview.value = {
          ...info.event.extendedProps,
          id: info.event.id,
          title: info.event.title,
          start: info.event.start,
          end: info.event.end
        };
        isModalOpen.value = true;
      },
      eventClassNames: (arg) => {
        // Add status-based class for custom styling
        const status = arg.event.extendedProps.status || 'scheduled';
        return ['custom-event', `event-status-${status}`];
      }
    };

    function getEventColor(status) {
      switch (status) {
        case 'scheduled':
          return '#3b82f6'; // blue-500
        case 'completed':
          return '#10b981'; // emerald-500
        case 'cancelled':
          return '#ef4444'; // red-500
        case 'rescheduled':
          return '#f59e0b'; // amber-500
        default:
          return '#6b7280'; // gray-500
      }
    }

    function changeView(action) {
      const calendarApi = calendar.value.getApi();
      switch (action) {
        case 'prev':
          calendarApi.prev();
          break;
        case 'next':
          calendarApi.next();
          break;
        case 'today':
          calendarApi.today();
          calendarApi.changeView('dayGridMonth');
          break;
      }
    }

    function formatTime(date) {
      return format(new Date(date), 'HH:mm');
    }

    function formatDateTime(date) {
      if (!date) return '';
      return format(new Date(date), 'PPPppp');
    }

    function closeModal() {
      isModalOpen.value = false;
      selectedInterview.value = null;
      isMapLoaded.value = false;
      if (mapInstance) {
        mapInstance = null;
      }
    }

    function isValidHttpUrl(string) {
      try {
        const url = new URL(string);
        return url.protocol === 'http:' || url.protocol === 'https:';
      } catch (_) {
        return false;
      }
    }

    function formatDate(date) {
      if (!date) return '';
      return format(parseISO(date), 'PPP');
    }

    function formatDateTime(date) {
      if (!date) return '';
      return format(parseISO(date), 'PPPppp');
    }

    async function initMap() {
      if (!selectedInterview.value?.location || !mapContainer.value) return;
      
      isMapLoaded.value = false;
      
      // Only try to load Google Maps if it's available
      if (typeof google === 'undefined' || !google.maps) {
        console.error('Google Maps API not loaded');
        return;
      }
      
      try {
        // Use the Geocoding API to convert address to coordinates
        const geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({ address: selectedInterview.value.location }, (results, status) => {
          if (status === 'OK' && results[0]) {
            const location = results[0].geometry.location;
            
            // Create a new map instance
            mapInstance = new google.maps.Map(mapContainer.value, {
              center: location,
              zoom: 15,
              disableDefaultUI: true,
              zoomControl: true,
              styles: [
                {
                  featureType: 'poi',
                  elementType: 'labels',
                  stylers: [{ visibility: 'off' }]
                }
              ]
            });
            
            // Add a marker at the location
            new google.maps.Marker({
              position: location,
              map: mapInstance,
              title: selectedInterview.value.company || 'Interview Location'
            });
            
            isMapLoaded.value = true;
          } else {
            console.error('Geocode was not successful for the following reason:', status);
            isMapLoaded.value = false;
          }
        });
      } catch (error) {
        console.error('Error initializing map:', error);
        isMapLoaded.value = false;
      }
    }

    // Watch for when the modal opens and initialize the map
    onMounted(() => {
      // You can add any initialization code here
    });
    
    // Watch for changes to the selected interview
    watch(() => selectedInterview.value, async (newVal) => {
      if (newVal && newVal.location) {
        await nextTick();
        initMap();
      }
    });
    
    onBeforeUnmount(() => {
      if (mapInstance) {
        // Clean up the map instance when component is unmounted
        mapInstance = null;
      }
    });

    return {
      calendar,
      calendarOptions,
      changeView,
      formatTime,
      formatDate,
      formatDateTime,
      isModalOpen,
      selectedInterview,
      closeModal,
      mapContainer,
      isMapLoaded,
      isValidHttpUrl
    };
  }
};
</script>

<style>
/* FullCalendar overrides */
.calendar {
  --fc-border-color: #e5e7eb; /* gray-200 */
  --fc-now-indicator-color: #ef4444; /* red-500 */
  --fc-today-bg-color: #f9fafb; /* gray-50 */
  --fc-highlight-color: #eff6ff; /* blue-50 */
  --fc-event-bg-color: #3b82f6; /* blue-500 */
  --fc-event-border-color: #2563eb; /* blue-600 */
  --fc-event-text-color: #ffffff; /* white */
  --fc-page-bg-color: #ffffff; /* white */
  --fc-neutral-bg-color: #f3f4f6; /* gray-100 */
  --fc-list-event-hover-bg-color: #f9fafb; /* gray-50 */
}

/* Dark mode overrides */
.dark .calendar {
  --fc-border-color: #374151; /* gray-700 */
  --fc-today-bg-color: #1f2937; /* gray-800 */
  --fc-highlight-color: #1e3a8a; /* blue-900 */
  --fc-page-bg-color: #111827; /* gray-900 */
  --fc-neutral-bg-color: #1f2937; /* gray-800 */
  --fc-list-event-hover-bg-color: #1f2937; /* gray-800 */
  --fc-now-indicator-color: #ef4444; /* red-500 */
}

/* Custom event styling */
.fc .fc-event {
  border: 1px solid var(--fc-event-border-color);
  border-radius: 0.25rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: opacity 0.2s;
}

.fc .fc-event:hover {
  opacity: 0.9;
}

.fc .fc-daygrid-event {
  margin: 0.125rem;
}

.fc .fc-daygrid-day.fc-day-today {
  background-color: rgba(219, 234, 254, 0.3); /* blue-50 with opacity */
}

.dark .fc .fc-daygrid-day.fc-day-today {
  background-color: rgba(30, 58, 138, 0.2); /* blue-900 with opacity */
}

.fc .fc-button {
  background-color: #ffffff;
  border: 1px solid #d1d5db; /* gray-300 */
  color: #374151; /* gray-700 */
  padding: 0.25rem 0.75rem;
  font-size: 0.875rem; /* text-sm */
  font-weight: 500; /* font-medium */
  border-radius: 0.375rem; /* rounded-md */
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s, border-color 0.2s, color 0.2s;
}

.fc .fc-button:hover {
  background-color: #f9fafb; /* gray-50 */
}

.fc .fc-button:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5); /* indigo-500 with opacity */
}

.dark .fc .fc-button {
  background-color: #374151; /* gray-700 */
  border-color: #4b5563; /* gray-600 */
  color: #f3f4f6; /* gray-200 */
}

.dark .fc .fc-button:hover {
  background-color: #4b5563; /* gray-600 */
}

.fc .fc-button-active {
  background-color: #e0e7ff; /* indigo-100 */
  border-color: #a5b4fc; /* indigo-300 */
  color: #4338ca; /* indigo-700 */
}

.dark .fc .fc-button-active {
  background-color: #1e1b4b; /* indigo-900 */
  border-color: #3730a3; /* indigo-700 */
  color: #c7d2fe; /* indigo-200 */
}

.fc .fc-toolbar-title {
  font-size: 1.125rem; /* text-lg */
  font-weight: 600; /* font-semibold */
  color: #111827; /* gray-900 */
}

.dark .fc .fc-toolbar-title {
  color: #f9fafb; /* white */
}

.fc .fc-col-header-cell-cushion {
  font-size: 0.875rem; /* text-sm */
  font-weight: 500; /* font-medium */
  color: #6b7280; /* gray-500 */
}

.dark .fc .fc-col-header-cell-cushion {
  color: #9ca3af; /* gray-400 */
}

.fc .fc-daygrid-day-number {
  font-size: 0.875rem; /* text-sm */
  color: #374151; /* gray-700 */
  padding: 0.5rem;
}

.dark .fc .fc-daygrid-day-number {
  color: #e5e7eb; /* gray-200 */
}

.fc .fc-day-today .fc-daygrid-day-number {
  font-weight: 700; /* font-bold */
  color: #4f46e5; /* indigo-600 */
}

.dark .fc .fc-day-today .fc-daygrid-day-number {
  color: #818cf8; /* indigo-400 */
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .fc .fc-toolbar {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .fc .fc-toolbar-chunk {
    width: 100%;
    display: flex;
    justify-content: space-between;
  }
}
</style>
