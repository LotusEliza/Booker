// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/store'
import VueResource from 'vue-resource'
let Bootstrap = require('bootstrap');
import jQuery from 'jquery'
global.jQuery = jQuery;
import 'bootstrap/dist/css/bootstrap.css'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'mdbvue/build/css/mdb.css';
import Vuex from 'vuex';
import axios from 'axios'


Vue.use(BootstrapVue);
Vue.use(VueResource);
Vue.config.productionTip = false;
Vue.use(Vuex);

export const HTTP = axios.create({
    baseURL: 'http://tc.geeksforless.net/~user3/booker/server/api/'
})

Vue.directive("theme", {
  bind(el, binding, vnode){
    if(binding.value == 'wide'){
      el.style.maxWidth = "1200px";
    }else if(binding.value == 'narrow'){
        el.style.maxWidth = "500px";
    }

    if(binding.arg == 'column'){
        el.style.background = '#ddd';
        el.style.padding='20px';
    }
  }
});

new Vue({
  store,
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})