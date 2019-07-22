import Vue from 'vue'
import Router from 'vue-router'
import home from '@/components/home'
import calendar from '@/components/calendar'
import book from '@/components/book'
import employee from '@/components/employee'
import editEmployee from '@/components/editEmployee'
import notFound from '../views/notFound'
import store from '../store/store.js'

Vue.use(Router)

let router =  new Router({
  mode: 'history',
  base: '/~user3/booker/client/',
  routes: [
      {
          path: '/',
          name: 'home',
          component: home
      },
      {
          path: '/book',
          name: 'book',
          component: book,
          meta: {
              requiresAuth: true
          }
      },
      {
          path: '/edit',
          name: 'editEmployee',
          component: editEmployee
      },
      {
          path: '/employee',
          name: 'employee',
          component: employee
      },
      {
          path: '/calendar',
          name: 'calendar',
          component: calendar,
          meta: {
              requiresAuth: true
          }
      },
      {
          path: '/404',
          name: '404',
          component: notFound
      },
      {
          path: '*',
          redirect: '/404'
      },
  ]
})

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next()
            return
        }
        next('/')
    } else {
        next()
    }
})

export default router