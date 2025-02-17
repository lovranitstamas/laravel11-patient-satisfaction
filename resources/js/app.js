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

//const app = createApp(App);
let app = store.state.blade === 1 ? createApp() : createApp(App);

// ! ==== BOOTSTRAP ==== ! //
import {createBootstrap} from 'bootstrap-vue-next';
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

  if (store.state.blade === 0) {
    app.component(componentName, componentConfig.default || componentConfig);
  } else {
    if (componentName !== 'App') {
      app.component(componentName, componentConfig.default || componentConfig);
    }
  }
});
// ! ==== END OF VUE COMPONENTS ==== ! //


// ! ==== VUE ROUTER ==== ! //
import router from './router';
app.use(router)
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

// ! ==== DEBOUNCE VUETIFY ==== ! //
import {vueDebounce} from "vue-debounce";
// ! ==== END OF DEBOUNCE ==== ! //

// ! ==== INTERCEPTOR ==== ! //
import {
  SET_SNACKBAR,
  TOGGLE_SNACKBAR
} from './store/constants'

app.mixin({
  data() {
    return {
      isLoading: false,
    }
  },
  beforeCreate() {
    axios.interceptors.request.use(
        req => {
          this.isLoading = true
          return req
        },
        err => {
          this.isLoading = false
          return Promise.reject(err)
        },
    )

    axios.interceptors.response.use(
        resp => {
          this.isLoading = false
          if (resp.data.errors) {
            //console.log(resp.data.errors);
            //console.log(resp.data.errors.filter(e => e.message === 'validation').map(e => Object.values(e.extensions.validation))); // [0] [0]
            //console.log(resp.data.errors.filter(e => e.message === 'validation').map(e => Object.values(e.extensions.validation).flat())); // [0]
            //console.log(resp.data.errors.filter(e => e.message === 'validation').map(e => Object.values(e.extensions.validation).flat()).flat()); //['er']
            const messages = resp.data.errors.filter(e => e.message === 'validation').map(e => Object.values(e.extensions.validation).flat()).flat()
            if (messages.length) {

              store.commit(SET_SNACKBAR, {show: true, messages: messages})
              // to block the default popup window and lis
              return Promise.reject(null)
            }
            // return Promise.reject(resp.data.errors)
            // we manage the error in store module in then case
            return resp
          }
          return resp
        },
        err => {
          this.isLoading = false
          // we must put all key in conditional because, we may get null response from previous case
          if (err?.response?.status === 422 || err?.message?.extensions?.category === 'validation') {
            store.commit(TOGGLE_SNACKBAR,
                {show: true, messages: [err.response.data.message], color: '#b45309'})
          }
          if (err?.response?.status === 500) {
            const date = new Date()
            const dateTime = `${date.toLocaleDateString('hu')} ${date.toLocaleTimeString('hu')}`
            store.commit(TOGGLE_SNACKBAR,
                {
                  show: true,
                  messages: [`Szerverhiba történt! Próbálja újra vagy kérjen segítséget az üzemeltetőtől - Hibakód: 500 - ${dateTime}`],
                })
          }
          return Promise.reject(err)
        },
    )
  },
})

app.directive('debounce', vueDebounce({lock: true}))
    .mount('#app');
