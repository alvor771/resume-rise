// Test file to check environment variables
console.log('VITE_API_URL:', import.meta.env.VITE_API_URL);
console.log('VITE_APP_ENV:', import.meta.env.VITE_APP_ENV);
console.log('VITE_BASE_URL:', import.meta.env.VITE_BASE_URL);

export default {
  VITE_API_URL: import.meta.env.VITE_API_URL,
  VITE_APP_ENV: import.meta.env.VITE_APP_ENV,
  VITE_BASE_URL: import.meta.env.VITE_BASE_URL
};
