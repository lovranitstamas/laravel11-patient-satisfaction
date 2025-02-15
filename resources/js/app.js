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

// Automatikus Vue komponens regisztráció
const files = require.context("./", true, /\.vue$/i);
files.keys().map(key => {
  const componentConfig = files(key);
  const componentName = key.split('/').pop().split('.')[0];
  app.component(componentName, componentConfig.default || componentConfig);
});

app.mount('#app');

// ! ==== AUTO VUE COMPONENT REGISTRATION ==== ! //
