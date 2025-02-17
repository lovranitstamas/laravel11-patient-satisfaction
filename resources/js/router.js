import {createRouter, createWebHistory} from 'vue-router';

import HomeComponent from "./components/HomeComponent";
import SurveyComponent from "./components/pages/SurveyComponent.vue";
import QuestionnaireComponent from "./components/pages/QuestionnaireComponent.vue";
import ResponseComponent from "./components/pages/ResponseComponent.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeComponent
    },
    {
      path: '/survey',
      name: 'survey',
      component: SurveyComponent
    },
    {
      path: '/questionnaire',
      name: 'questionnaire',
      component: QuestionnaireComponent
    },
    {
      path: '/response',
      name: 'response',
      component: ResponseComponent
    }
  ]
});

export default router;