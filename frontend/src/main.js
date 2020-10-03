/*!

=========================================================
* BootstrapVue Argon Dashboard - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/bootstrap-vue-argon-dashboard
* Copyright 2020 Creative Tim (https://www.creative-tim.com)

* Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
import Vue from 'vue';
import DashboardPlugin from './plugins/dashboard-plugin';
import App from './App.vue';
import axios from 'axios'
import VueAxios from 'vue-axios'
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
require('dotenv').config()


import configPlugin from '../config'
Vue.use(configPlugin)

Vue.use(VueAxios, axios)
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
// router setup
import router from './routes/router';
// plugin setup
Vue.use(DashboardPlugin);

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.typeAdmin)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      var admin = localStorage.getItem("typeAdmin");
      if (admin == null) {
          next({
              path: "/admin/auth/login",
              query: { redirect: to.fullPath }
          });
      } else {
          next();
      }
  } else {
      next(); // make sure to always call next()!
  }
  if (to.matched.some(record => record.meta.typeTutor)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      var tutor = localStorage.getItem("typeTutor");
      if (tutor == null) {
          next({
              path: "/auth",
              query: { redirect: to.fullPath }
          });
      } else {
          next();
      }
  } else {
      next(); // make sure to always call next()!
  }
  if (to.matched.some(record => record.meta.typeStudent)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      var student = localStorage.getItem("typeStudent");
      if (student == null) {
          next({
              path: "/auth",
              query: { redirect: to.fullPath }
          });
      } else {
          next();
      }
  } else {
      next(); // make sure to always call next()!
  }
});



/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router
});
