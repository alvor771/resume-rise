<template>
  <div class="culture-match">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-sm font-medium text-gray-700">Culture Match</h3>
      <span 
        class="text-sm font-medium"
        :class="{
          'text-green-600': matchPercentage >= 80,
          'text-blue-600': matchPercentage >= 60 && matchPercentage < 80,
          'text-yellow-600': matchPercentage >= 40 && matchPercentage < 60,
          'text-red-600': matchPercentage < 40
        }"
      >
        {{ matchPercentage }}%
      </span>
    </div>
    
    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-2.5">
      <div 
        class="h-2.5 rounded-full"
        :class="{
          'bg-green-500': matchPercentage >= 80,
          'bg-blue-500': matchPercentage >= 60 && matchPercentage < 80,
          'bg-yellow-500': matchPercentage >= 40 && matchPercentage < 60,
          'bg-red-500': matchPercentage < 40
        }"
        :style="{ width: `${matchPercentage}%` }"
      ></div>
    </div>
    
    <!-- Match Details -->
    <div v-if="showDetails" class="mt-4 space-y-3">
      <div 
        v-for="(factor, index) in matchFactors" 
        :key="index"
        class="flex items-center justify-between"
      >
        <span class="text-xs text-gray-600">{{ factor.name }}</span>
        <div class="flex items-center">
          <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden mr-2">
            <div 
              class="h-full"
              :class="{
                'bg-green-500': factor.score >= 80,
                'bg-blue-500': factor.score >= 60 && factor.score < 80,
                'bg-yellow-500': factor.score >= 40 && factor.score < 60,
                'bg-red-500': factor.score < 40
              }"
              :style="{ width: `${factor.score}%` }"
            ></div>
          </div>
          <span class="text-xs font-medium w-8 text-right">{{ factor.score }}%</span>
        </div>
      </div>
    </div>
    
    <!-- Match Summary -->
    <div v-if="showSummary" class="mt-3 p-3 bg-blue-50 rounded-md">
      <p class="text-xs text-blue-700">
        <span class="font-medium">Why this matters:</span> 
        {{ matchSummary }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  matchPercentage: {
    type: Number,
    required: true,
    validator: value => value >= 0 && value <= 100
  },
  matchFactors: {
    type: Array,
    default: () => [
      { name: 'Values', score: 0 },
      { name: 'Work Style', score: 0 },
      { name: 'Skills', score: 0 },
      { name: 'Goals', score: 0 }
    ],
    validator: factors => 
      factors.every(factor => 
        typeof factor === 'object' && 
        'name' in factor && 
        'score' in factor &&
        factor.score >= 0 && 
        factor.score <= 100
      )
  },
  showDetails: {
    type: Boolean,
    default: true
  },
  showSummary: {
    type: Boolean,
    default: false
  }
});

const matchSummary = computed(() => {
  if (props.matchPercentage >= 80) {
    return 'Excellent match! Your values, work style, and goals align very well.';
  } else if (props.matchPercentage >= 60) {
    return 'Good match! You share many important values and work styles.';
  } else if (props.matchPercentage >= 40) {
    return 'Moderate match. You have some alignment but also key differences to consider.';
  } else {
    return 'Limited match. There may be significant differences in values and work styles.';
  }
});
</script>
