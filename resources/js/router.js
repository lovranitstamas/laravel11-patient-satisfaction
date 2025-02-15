import {createRouter, createWebHistory} from 'vue-router';

import HomeComponent from "./components/HomeComponent";
import TestComponent from "./components/pages/TestComponent.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeComponent
    },
    {
      path: '/test',
      name: 'test',
      component: TestComponent
    }
  ]
});

export default router;