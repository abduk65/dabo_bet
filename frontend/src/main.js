import { createApp } from 'vue'
import { createPinia } from 'pinia'
import VueDatePicker from '@vuepic/vue-datepicker'
import App from './App.vue'
import router from './router'
import { useMainStore } from '@/stores/main.js'
import './css/main.css'

// Init Pinia
const pinia = createPinia()

//translation
const i18n = createI18n({
  locale: 'am',
  silentTranslationWarn: true,
  fallbackWarn: false,
  missingWarn: false,
  fallbackLocale: 'en',
  messages: {
    en: {
      message: {
        hello: 'hello world'
      }
    },
    am: {
      "dashboard": "Dashboarddddddd",
      "Daily Sales": "ሽያጭ",
      "Unit":  "ዩኒት"
    }
  }
})
export function tran(key) {
  return i18n.global.t(key)
}


// Create Vue app
const app = createApp(App).use(router).use(pinia).use(i18n)
app.component('FormControl', FormControl)
app.mount('#app')
// Init main store
const mainStore = useMainStore(pinia)

// Fetch sample data
mainStore.fetchSampleClients()
mainStore.fetchSampleHistory()

// Dark mode
// Uncomment, if you'd like to restore persisted darkMode setting, or use `prefers-color-scheme: dark`. Make sure to uncomment localStorage block in src/stores/darkMode.js
import { useDarkModeStore } from './stores/darkMode'
import { createI18n } from 'vue-i18n'
import FormControl from '@/components/form/FormControl.vue'
import MyComponent from '@/views/MyComponent.vue'

const darkModeStore = useDarkModeStore(pinia)

if (
  (!localStorage['darkMode'] && window.matchMedia('(prefers-color-scheme: dark)').matches) ||
  localStorage['darkMode'] === '1'
) {
  darkModeStore.set(true)
}

// Default title tag
const defaultDocumentTitle = 'Admin One Vue 3 Tailwind'

// Set document title from route meta
router.afterEach((to) => {
  document.title = to.meta?.title
    ? `${to.meta.title} — ${defaultDocumentTitle}`
    : defaultDocumentTitle
})


export {i18n}
