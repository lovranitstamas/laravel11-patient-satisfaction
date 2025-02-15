import './bootstrap';

import './bootstrap';

window.Vue = require('vue').default;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// ! ==== AUTO VUE COMPONENT REGISTRATION ==== ! //

import {createApp} from 'vue';
import App from './App.vue';

const app = createApp(App);

// ! ==== BOOTSTRAP ==== ! //
import { createBootstrap } from 'bootstrap-vue-next';
import * as BootstrapVueNext from 'bootstrap-vue-next';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

app.use(createBootstrap())

//registration all components (for example BBadge)
for (const componentName in BootstrapVueNext) {
  if (Object.prototype.hasOwnProperty.call(BootstrapVueNext, componentName)) {
    app.component(componentName, BootstrapVueNext[componentName]);
  }
}
// ! ==== END OF BOOTSTRAP ==== ! //


// ! ==== VUE COMPONENTS ==== ! //
const files = require.context("./", true, /\.vue$/i);
files.keys().map(key => {
  const componentConfig = files(key);
  const componentName = key.split('/').pop().split('.')[0];
  app.component(componentName, componentConfig.default || componentConfig);
});
// ! ==== END OF VUE COMPONENTS ==== ! //


// ! ==== VUE ROUTER ==== ! //
import router from './router';
app.use(router);

app.mount('#app');
// ! ==== END OF VUE ROUTER ==== ! //


// ! ==== VUE STORE ==== ! //
import store from "./store/store";
app.use(store);
// ! ==== END OF VUE STORE ==== ! //


// ! ==== VUETIFY ==== ! //
import {createVuetify} from 'vuetify';
import 'vuetify/styles';
import {aliases, mdi} from 'vuetify/iconsets/mdi';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {mdi},
  },
});
app.use(vuetify);
// ! ==== END OF VUETIFY ==== ! //

window.domainHttps = window.location.protocol + "//" + window.location.host === 'http://127.0.0.1:8000' ?
    '' : "https://lovranitstamas.eu/laravel11-patient-satisfaction/public";

// ! ==== AUTO VUE COMPONENT REGISTRATION ==== ! //
