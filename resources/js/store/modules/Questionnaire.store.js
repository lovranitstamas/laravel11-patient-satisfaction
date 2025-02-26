import {PAGINATION} from "../constants";

export const TYPES = {
  actions: {
    loadQuestionData: "loadQuestionData"
  },
  mutations: {
    getQuestions: "getQuestions",
    getUserQuestions: "getUserQuestions",
    storeQuestion: "storeQuestion",
    updateQuestion: "updateQuestion",
    deleteQuestion: "deleteQuestion",
  },
};

const initialState = () => ({
  mobileHeaders: [
    {
      title: "ID<br>Kérdés",
      value: "id_question",
      align: 'center',
      sortable: false,
      width: '100%'
    },
  ],
  headers: [
    {
      title: "ID",
      value: "id",
      align: 'center',
      sortable: false,
      width: '10%'
    },
    {
      title: "Kérdőív neve",
      value: "survey.name",
      align: 'center',
      sortable: false,
      width: '20%'
    },
    {
      title: "Kérdés",
      value: "question",
      align: 'center',
      sortable: false,
      width: '60%'
    },
    {
      title: "Lehetőségek",
      value: "actions",
      align: 'center',
      sortable: false,
      width: '10%'
    },
  ],
})

const state = {
  ...initialState(),
  questions: [],
  userQuestions: [],
  questionsInitStateLength: 0,
  questionsQueryResponse:
      `
    id,
    exists_in_responses,
    question,
    survey {
      id
      name
    },
    created_at
    `,
  userQuestionsQueryResponse:
      `
    id,
    question
    `,
}

const actions = {
  //user
  getUserQuestionnaire({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const QUERY_NAME = 'usersQuestionsQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `{
                ${QUERY_NAME} {
                   ${state.userQuestionsQueryResponse}
              }
           }`
          })
          .then(r => r.data.data[QUERY_NAME])
          .then(r => {
            commit(TYPES.mutations.getUserQuestions, r);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  //admin
  getQuestionData({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const {search, orderBy, surveyId} = payload;

    const QUERY_NAME = 'questionsQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `query ${QUERY_NAME} ($survey_id:Int, $per_page:Int, $current_page:Int, $search:String, $orderBy:String){
          ${QUERY_NAME} (survey_id:$survey_id, per_page:$per_page,current_page:$current_page,search:$search,orderBy:$orderBy){
             data{
                ${state.questionsQueryResponse}
             },
             ${PAGINATION}
          }
        }`,
            variables: {
              survey_id: surveyId,
              current_page: rootState.Table.current_page,
              per_page: rootState.Table.per_page,

              search: search,
              orderBy: orderBy
            },
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then(r => {
            if (r.data.errors) {
              if (r.data.errors[0]?.extensions?.debugMessage) {
                return Promise.reject(new Error(r.data.errors[0].extensions.debugMessage));
              } else {
                const errorMessage = r.data.errors[0].message || 'An unknown error occurred.';
                return Promise.reject(new Error(errorMessage));
              }
            }

            return r.data.data[QUERY_NAME];
          })
          .then(res => {
            commit(TYPES.mutations.getQuestions, res['data']);
            // set footer
            dispatch(
                "Table/rowsLoaded",
                {
                  last_page: res['last_page']
                },
                {root: true}
            );
            resolve();
          }).catch((err) => {
            // return Promise.reject(err);
            reject(err);
          });
    })
  },
  storeQuestionData({commit, rootGetters, dispatch, rootState}, {questionnaire}) {

    const surveyId = questionnaire.survey_id;
    const question = questionnaire.question;

    const MUTATION_NAME = 'storeQuestionMutation';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $survey_id:Int!,
                    $question:String!
                 ) {
                      ${MUTATION_NAME}(
                        survey_id:$survey_id,
                        question:$question
                      ) {
                        ${state.questionsQueryResponse}
                      }
                    }                           
                `,
            variables: {
              survey_id: surveyId,
              question: question,
            },
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then(r => {
            if (r.data.errors) {
              if (r.data.errors[0]?.extensions?.debugMessage) {
                return Promise.reject(new Error(r.data.errors[0].extensions.debugMessage));
              } else {
                const errorMessage = r.data.errors[0].message || 'An unknown error occurred.';
                return Promise.reject(new Error(errorMessage));
              }
            }

            return r.data.data[MUTATION_NAME];
          })
          .then(res => {
            commit(TYPES.mutations.storeQuestion, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  updateQuestionData({commit, rootGetters, dispatch, rootState}, {questionnaire}) {

    const id = questionnaire.id;
    const surveyId = questionnaire.survey_id;  //will be removed - not necessary
    const question = questionnaire.question;

    const MUTATION_NAME = 'updateQuestionMutation';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $id:Int!,
                    $survey_id:Int!,
                    $question:String!
                 ) {
                      ${MUTATION_NAME}(
                        id:$id,
                        survey_id:$survey_id,
                        question:$question
                      ) {
                        ${state.questionsQueryResponse}
                      }
                    }                           
                `,
            variables: {
              id: id,
              survey_id: surveyId,
              question: question,
            },
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then(r => {
            if (r.data.errors) {
              if (r.data.errors[0]?.extensions?.debugMessage) {
                return Promise.reject(new Error(r.data.errors[0].extensions.debugMessage));
              } else {
                const errorMessage = r.data.errors[0].message || 'An unknown error occurred.';
                return Promise.reject(new Error(errorMessage));
              }
            }

            return r.data.data[MUTATION_NAME];
          })
          .then(res => {
            commit(TYPES.mutations.updateQuestion, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  deleteQuestion({commit, rootGetters, dispatch, rootState}, {questionnaire}) {

    const id = questionnaire.id;

    const MUTATION_NAME = 'deleteQuestionMutation';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $id:Int!
                 ) {
                      ${MUTATION_NAME}(
                        id:$id
                      ) {
                        ${state.questionsQueryResponse}
                      }
                    }                           
                `,
            variables: {
              id: id
            },
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then(r => {
            if (r.data.errors) {
              if (r.data.errors[0]?.extensions?.debugMessage) {
                return Promise.reject(new Error(r.data.errors[0].extensions.debugMessage));
              } else {
                const errorMessage = r.data.errors[0].message || 'An unknown error occurred.';
                return Promise.reject(new Error(errorMessage));
              }
            }

            return r.data.data[MUTATION_NAME];
          })
          .then(res => {
            commit(TYPES.mutations.deleteQuestion, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  }
};

const mutations = {
  [TYPES.mutations.getQuestions](state, payload) {
    state.questions = payload;

    if (state.questionsInitStateLength === 0) {
      state.questionsInitStateLength = payload.filter(e => {
        return e;
      }).length;
    }
  },
  [TYPES.mutations.getUserQuestions](state, payload) {
    state.userQuestions = payload;
  },
  [TYPES.mutations.storeQuestion](state, payload) {
    state.questions.unshift(payload)
  },
  [TYPES.mutations.updateQuestion](state, payload) {
    state.questions.map(entry => {
      if (entry.id === payload.id) {
       entry.survey_id = payload.survey_id
       entry.question = payload.question
      }
    });
  },
  [TYPES.mutations.deleteQuestion](state, payload) {
    state.questions = state.questions.filter(entry => entry.id !== payload.id);
  }
};
const getters = {
  headers: (state) => state.headers,
  mobileHeaders: (state) => state.mobileHeaders,
  questionCollection: (state) => state.questions.filter(e => {
    return e;
  }).map(item => ({
    ...item,
    id_question: `<b>${item.id}</b><br>${item.question || ''}`
  })),
  userQuestionCollection: (state) => state.userQuestions,
  questionCollectionInitStateLength: (state) => state.questionsInitStateLength
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
