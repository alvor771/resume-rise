<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Activity by Region</h3>
      <div class="flex items-center space-x-2">
        <select 
          v-model="selectedMetric" 
          class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
          <option value="users">Users</option>
          <option value="sessions">Sessions</option>
          <option value="resumes">Resumes Created</option>
        </select>
      </div>
    </div>
    <div ref="mapContainer" class="w-full h-96 relative">
      <div v-if="loading" class="absolute inset-0 flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>
      <svg ref="mapSvg" class="w-full h-full"></svg>
      <div v-if="!loading" class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
        Hover over a country to see details
      </div>
    </div>
    <div v-if="selectedCountry" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
      <h4 class="font-medium text-gray-900 dark:text-white">{{ selectedCountry.name }}</h4>
      <div class="grid grid-cols-3 gap-4 mt-2">
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400">Users</p>
          <p class="font-semibold">{{ selectedCountry.users?.toLocaleString() || '0' }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400">Sessions</p>
          <p class="font-semibold">{{ selectedCountry.sessions?.toLocaleString() || '0' }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400">Resumes</p>
          <p class="font-semibold">{{ selectedCountry.resumes?.toLocaleString() || '0' }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import * as d3 from 'd3';
import { geoMercator, geoPath } from 'd3-geo';
import * as topojson from 'topojson-client';

// Prefix with underscore to indicate it's intentionally unused
const _props = defineProps({
  data: {
    type: Array,
    default: () => []
  }
});

const mapContainer = ref(null);
const mapSvg = ref(null);
const loading = ref(true);
const selectedMetric = ref('users');
const selectedCountry = ref(null);
const projection = ref(null);
const path = ref(null);
const colorScale = ref(null);

// Mock data - replace with actual API call
const fetchMapData = async () => {
  try {
    // In a real app, you would fetch this from your API
    // const response = await fetch('/api/admin/analytics/map-data');
    // return await response.json();
    
    // Mock data
    await new Promise(resolve => setTimeout(resolve, 1000));
    return [
      { country: 'USA', users: 1500, sessions: 4500, resumes: 850 },
      { country: 'GBR', users: 1200, sessions: 3800, resumes: 720 },
      { country: 'CAN', users: 800, sessions: 2400, resumes: 450 },
      { country: 'AUS', users: 650, sessions: 1900, resumes: 380 },
      { country: 'DEU', users: 900, sessions: 2700, resumes: 520 },
      { country: 'FRA', users: 750, sessions: 2200, resumes: 410 },
      { country: 'JPN', users: 1100, sessions: 3300, resumes: 620 },
      { country: 'BRA', users: 500, sessions: 1500, resumes: 280 },
      { country: 'IND', users: 2000, sessions: 6000, resumes: 1100 },
      { country: 'ZAF', users: 300, sessions: 900, resumes: 170 }
    ];
  } catch (error) {
    console.error('Error fetching map data:', error);
    return [];
  }
};

const renderMap = async () => {
  loading.value = true;
  
  try {
    // Load world map data
    const worldMap = await d3.json('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json');
    const countries = topojson.feature(worldMap, worldMap.objects.countries).features;
    
    // Get metrics data
    const metricsData = await fetchMapData();
    
    // Create a lookup for country metrics
    const metricsByCountry = {};
    metricsData.forEach(d => {
      metricsByCountry[d.country] = d;
    });
    
    // Set up map dimensions
    const width = mapContainer.value.clientWidth;
    const height = 500;
    
    // Create projection
    projection.value = geoMercator()
      .fitSize([width, height], { type: 'FeatureCollection', features: countries });
    
    // Create path generator
    path.value = geoPath().projection(projection.value);
    
    // Create color scale
    const maxValue = d3.max(metricsData, d => d[selectedMetric.value]) || 1;
    colorScale.value = d3.scaleSequential()
      .domain([0, maxValue])
      .interpolator(d3.interpolateBlues);
    
    // Clear previous map
    d3.select(mapSvg.value).selectAll('*').remove();
    
    // Draw countries
    d3.select(mapSvg.value)
      .selectAll('path')
      .data(countries)
      .enter()
      .append('path')
      .attr('d', path.value)
      .attr('class', 'country')
      .style('fill', d => {
        const countryData = metricsByCountry[d.id] || {};
        return colorScale.value(countryData[selectedMetric.value] || 0);
      })
      .style('stroke', '#fff')
      .style('stroke-width', 0.5)
      .style('opacity', 0.8)
      .on('mouseover', function(event, d) {
        d3.select(this).style('opacity', 1).style('stroke-width', 2);
        const countryData = metricsByCountry[d.id] || {};
        selectedCountry.value = {
          name: d.properties.name,
          users: countryData.users,
          sessions: countryData.sessions,
          resumes: countryData.resumes
        };
      })
      .on('mouseout', function() {
        d3.select(this).style('opacity', 0.8).style('stroke-width', 0.5);
      });
    
    // Add zoom behavior
    const zoom = d3.zoom()
      .scaleExtent([1, 8])
      .on('zoom', (event) => {
        d3.select(mapSvg.value).selectAll('path')
          .attr('transform', event.transform);
      });
    
    d3.select(mapSvg.value).call(zoom);
    
  } catch (error) {
    console.error('Error rendering map:', error);
  } finally {
    loading.value = false;
  }
};

// Watch for metric changes
watch(selectedMetric, () => {
  if (mapSvg.value) {
    renderMap();
  }
});

// Initialize map when component mounts
onMounted(() => {
  renderMap();
  window.addEventListener('resize', renderMap);
});
</script>

<style scoped>
.country {
  transition: fill 0.2s, opacity 0.2s, stroke-width 0.2s;
  cursor: pointer;
}

.country:hover {
  opacity: 1 !important;
  stroke-width: 2px !important;
}
</style>
