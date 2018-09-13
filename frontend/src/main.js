import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'

import './css/main.css'

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
  color: '#38c172',
  failedColor: '#874b4b',
})

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
