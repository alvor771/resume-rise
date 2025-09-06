<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-white">Interview Schedule</h1>
      <button 
        @click="showNewEventModal = true"
        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg flex items-center"
      >
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        New Event
      </button>
    </div>

    <!-- Calendar View -->
    <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-white">
          {{ currentMonth }} {{ currentYear }}
        </h2>
        <div class="flex space-x-2">
          <button @click="previousMonth" class="p-1.5 hover:bg-gray-700/50 rounded-lg">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button @click="goToToday" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
            Today
          </button>
          <button @click="nextMonth" class="p-1.5 hover:bg-gray-700/50 rounded-lg">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Calendar Grid -->
      <div class="grid grid-cols-7 gap-1">
        <div v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" 
             :key="day"
             class="text-center text-sm font-medium text-gray-400 py-2">
          {{ day }}
        </div>
        
        <div v-for="day in startingDay" :key="'empty-' + day" class="h-10"></div>
        
        <div v-for="day in daysInMonth" 
             :key="day"
             @click="selectDate(day)"
             class="h-24 p-1.5 border rounded-lg cursor-pointer transition-colors"
             :class="{
               'border-gray-700 hover:bg-gray-700/30': !isSelected(day),
               'border-purple-500 bg-purple-500/10': isSelected(day),
               'bg-gray-700/50': isToday(day)
             }">
          <div class="flex justify-between">
            <span :class="{'font-bold': isToday(day)}">{{ day }}</span>
            <span v-if="hasEvents(day)" class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Selected Date Events -->
    <div v-if="selectedDateEvents.length > 0" class="space-y-3">
      <h3 class="text-lg font-semibold text-white">
        Events on {{ selectedDateDisplay }}
      </h3>
      <div v-for="event in selectedDateEvents" :key="event.id" 
           class="gradient-card p-4 rounded-lg border border-gray-700/50">
        <div class="flex justify-between items-start">
          <div>
            <h4 class="font-medium text-white">{{ event.title }}</h4>
            <p class="text-sm text-gray-400">
              {{ formatTime(event.startTime) }} - {{ formatTime(event.endTime) }}
            </p>
          </div>
          <span class="px-2 py-1 text-xs rounded-full" 
                :class="{
                  'bg-blue-500/20 text-blue-300': event.type === 'interview',
                  'bg-purple-500/20 text-purple-300': event.type === 'meeting'
                }">
            {{ event.type }}
          </span>
        </div>
      </div>
    </div>
    
    <!-- New Event Modal -->
    <div v-if="showNewEventModal" class="fixed inset-0 bg-black/70 flex items-center justify-center p-4 z-50">
      <div class="bg-gray-800 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-white">New Event</h2>
          <button @click="showNewEventModal = false" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="createEvent" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
            <input v-model="newEvent.title" type="text" required
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-1">Date</label>
              <input v-model="newEvent.date" type="date" required
                     class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-1">Time</label>
              <input v-model="newEvent.time" type="time" required
                     class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 pt-2">
            <button type="button" @click="showNewEventModal = false"
                    class="px-4 py-2 text-sm text-gray-300 hover:text-white">
              Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 text-sm bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
              Create Event
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';

export default {
  name: 'ScheduleGrid',
  
  setup() {
    const currentDate = ref(new Date());
    const selectedDate = ref(new Date().getDate());
    const showNewEventModal = ref(false);
    
    const newEvent = ref({
      title: '',
      date: new Date().toISOString().split('T')[0],
      time: '10:00',
      type: 'meeting'
    });
    
    const events = ref([
      {
        id: 1,
        title: 'Interview with John',
        date: new Date().toISOString().split('T')[0],
        startTime: '14:00',
        endTime: '15:00',
        type: 'interview'
      },
      {
        id: 2,
        title: 'Team Meeting',
        date: new Date(Date.now() + 86400000).toISOString().split('T')[0],
        startTime: '10:00',
        endTime: '11:00',
        type: 'meeting'
      }
    ]);
    
    // Computed properties
    const currentMonth = computed(() => {
      return currentDate.value.toLocaleString('default', { month: 'long' });
    });
    
    const currentYear = computed(() => {
      return currentDate.value.getFullYear();
    });
    
    const daysInMonth = computed(() => {
      const year = currentDate.value.getFullYear();
      const month = currentDate.value.getMonth();
      return new Date(year, month + 1, 0).getDate();
    });
    
    const startingDay = computed(() => {
      const year = currentDate.value.getFullYear();
      const month = currentDate.value.getMonth();
      return new Date(year, month, 1).getDay();
    });
    
    const selectedDateEvents = computed(() => {
      const dateStr = `${currentYear.value}-${(currentDate.value.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.value.toString().padStart(2, '0')}`;
      return events.value.filter(event => event.date === dateStr);
    });
    
    const selectedDateDisplay = computed(() => {
      const date = new Date(currentDate.value);
      date.setDate(selectedDate.value);
      return date.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    });
    
    // Methods
    const isToday = (day) => {
      const today = new Date();
      return (
        day === today.getDate() &&
        currentDate.value.getMonth() === today.getMonth() &&
        currentDate.value.getFullYear() === today.getFullYear()
      );
    };
    
    const isSelected = (day) => {
      return day === selectedDate.value;
    };
    
    const hasEvents = (day) => {
      const dateStr = `${currentYear.value}-${(currentDate.value.getMonth() + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
      return events.value.some(event => event.date === dateStr);
    };
    
    const selectDate = (day) => {
      selectedDate.value = day;
    };
    
    const previousMonth = () => {
      const date = new Date(currentDate.value);
      date.setMonth(date.getMonth() - 1);
      currentDate.value = date;
    };
    
    const nextMonth = () => {
      const date = new Date(currentDate.value);
      date.setMonth(date.getMonth() + 1);
      currentDate.value = date;
    };
    
    const goToToday = () => {
      currentDate.value = new Date();
      selectedDate.value = currentDate.value.getDate();
    };
    
    const formatTime = (timeStr) => {
      const [hours, minutes] = timeStr.split(':');
      const hour = parseInt(hours, 10);
      return `${hour > 12 ? hour - 12 : hour}:${minutes} ${hour >= 12 ? 'PM' : 'AM'}`;
    };
    
    const createEvent = () => {
      const [hours, minutes] = newEvent.value.time.split(':');
      const newEventObj = {
        id: Date.now(),
        title: newEvent.value.title,
        date: newEvent.value.date,
        startTime: newEvent.value.time,
        endTime: `${parseInt(hours) + 1}:${minutes}`,
        type: newEvent.value.type
      };
      
      events.value.push(newEventObj);
      showNewEventModal.value = false;
      
      // Reset form
      newEvent.value = {
        title: '',
        date: new Date().toISOString().split('T')[0],
        time: '10:00',
        type: 'meeting'
      };
    };
    
    // Initialize
    onMounted(() => {
      // Initialize new event date to today
      newEvent.value.date = new Date().toISOString().split('T')[0];
    });
    
    return {
      currentDate,
      currentMonth,
      currentYear,
      daysInMonth,
      startingDay,
      selectedDate,
      selectedDateEvents,
      selectedDateDisplay,
      events,
      newEvent,
      showNewEventModal,
      isToday,
      isSelected,
      hasEvents,
      selectDate,
      previousMonth,
      nextMonth,
      goToToday,
      formatTime,
      createEvent
    };
  }
};
</script>
