import Vue from 'vue'
import Router from 'vue-router'
import Home from './components/Home.vue'
import Subdivision from './components/Subdivision'

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
      path: '/subdivisions',
      name: 'subdivisions',
      component: Subdivision
    }
  ]
})
