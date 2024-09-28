import '../assets/sass/app.scss';

import Vue from 'vue'
import store from "./store"
import { createInertiaApp } from '@inertiajs/vue2'
import router from './router';
import "./utils"

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    Vue.use(plugin)

    new Vue({
        store,
        router,
      render: h => h(App, props),
    }).$mount(el)
  },
})
