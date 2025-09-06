import { createPinia } from 'pinia';
import routes from './routes';
import { useCvSwapStore } from './store';
import CvSwapComponents from './components';

const CvSwapModule = {
  // Module name
  name: 'cv-swap',
  
  // Module routes
  routes,
  
  // Module store
  store: {
    'cv-swap': useCvSwapStore
  },
  
  // Module initialization
  install(app) {
    // Initialize Pinia if not already initialized
    if (!app.config.globalProperties.$pinia) {
      app.use(createPinia());
    }
    
    // Register the store
    useCvSwapStore(app.config.globalProperties.$pinia);
    
    // Register all components globally
    app.use(CvSwapComponents);
    
    console.log('CV-Swap module initialized');
  }
};

export default CvSwapModule;
