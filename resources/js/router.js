import {createRouter, createWebHistory} from 'vue-router';

import HomeComponent from "./components/HomeComponent";
import QuestionnaireComponent from "./components/pages/QuestionnaireComponent.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeComponent
    },
    {
      path: '/questionnaire',
      name: 'questionnaire',
      component: QuestionnaireComponent
    }
  ]
});

export default router;