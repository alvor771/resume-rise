<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">API Test</h1>
    <button 
      @click="testApi" 
      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      :disabled="loading"
    >
      {{ loading ? 'Testing...' : 'Test API Connection' }}
    </button>
    
    <div v-if="result" class="mt-4 p-4 bg-gray-100 rounded">
      <h2 class="text-lg font-semibold mb-2">Response:</h2>
      <pre class="whitespace-pre-wrap">{{ JSON.stringify(result, null, 2) }}</pre>
    </div>
    
    <div v-if="error" class="mt-4 p-4 bg-red-100 text-red-700 rounded">
      <h2 class="text-lg font-semibold">Error:</h2>
      <pre class="whitespace-pre-wrap">{{ error }}</pre>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import api from '@/services/api';

export default {
  name: 'ApiTest',
  setup() {
    const loading = ref(false);
    const result = ref(null);
    const error = ref(null);

    const testApi = async () => {
      loading.value = true;
      error.value = null;
      result.value = null;
      
      try {
        // Test a simple GET request to the API
        const response = await api.get('/sanctum/csrf-cookie');
        result.value = {
          status: response.status,
          statusText: response.statusText,
          data: response.data,
          headers: response.headers
        };
      } catch (err) {
        error.value = {
          message: err.message,
          response: err.response ? {
            status: err.response.status,
            statusText: err.response.statusText,
            data: err.response.data
          } : null
        };
        console.error('API Test Error:', err);
      } finally {
        loading.value = false;
      }
    };

    return {
      loading,
      result,
      error,
      testApi
    };
  }
};
</script>
