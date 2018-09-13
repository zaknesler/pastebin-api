import Vue from 'vue'
import Meta from 'vue-meta'
import Router from 'vue-router'
import Home from './views/Home.vue'

Vue.use(Meta)
Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/pastes',
      name: 'pastes.index',
      component: () => import('./views/pastes/Index.vue')
    },
    {
      path: '/pastes/new',
      name: 'pastes.create',
      component: () => import('./views/pastes/Create.vue')
    },
    {
      path: '/:slug',
      name: 'pastes.show',
      component: () => import('./views/pastes/Show.vue')
    }
  ]
})
