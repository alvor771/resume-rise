// Main module entry point
import routes from './routes'
import store from './store'
import locales from './locales'

export default {
  routes,
  store,
  locales,
  // Any module initialization can go here
  install(_Vue, _options) {
    // Module initialization code
  }
}
