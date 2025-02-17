import {PAGINATION} from "../constants";

export const TYPES = {
  mutations: {
    setSurvey: "setSurvey",

    //pagination
    getSurveys: "getSurveys",
    storeSurvey: "storeSurvey",
    updateSurvey: "updateSurvey",
    deleteSurvey: "deleteSurvey",
  },
};

const initialState = () => ({
  surveys: [],
  surveysQueryResponse:
      `
    id,
    name
    exists_in_responses
    `,
  //pagination
  surveysBasedOnPagination: [],
  surveysBasedOnPaginationInitStateLength: 0,

  mobileHeaders: [
    {
      title: "ID<br>Kérdés",
      value: "id_name",
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
      value: "name",
      align: 'center',
      sortable: false,
      width: '80%'
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
}

const actions = {
  getSurveyData({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const QUERY_NAME = 'surveysQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `{
                ${QUERY_NAME} {
                   ${state.surveysQueryResponse}
              }
           }`
          })
          .then(r => r.data.data[QUERY_NAME])
          .then(r => {
            commit(TYPES.mutations.setSurvey, r);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  getSurveyDataBasedOnPagination({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const {search, orderBy} = payload;

    const QUERY_NAME = 'surveysBasedOnPaginationQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `query ${QUERY_NAME} ($per_page:Int, $current_page:Int, $search:String, $orderBy:String){
          ${QUERY_NAME} (per_page:$per_page,current_page:$current_page,search:$search,orderBy:$orderBy){
             data{
                ${state.surveysQueryResponse}
             },
             ${PAGINATION}
          }
        }`,
            variables: {
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
            commit(TYPES.mutations.getSurveys, res['data']);
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
            reject(err);
          });
    })
  },
  storeSurveyData({commit, rootGetters, dispatch, rootState}, {survey}) {

    const name = survey.name;

    const MUTATION_NAME = 'storeSurveyMutation';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $name:String!
                 ) {
                      ${MUTATION_NAME}(
                        name:$name
                      ) {
                        ${state.surveysQueryResponse}
                      }
                    }                           
                `,
            variables: {
              name: name,
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
            commit(TYPES.mutations.storeSurvey, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  updateSurveyData({commit, rootGetters, dispatch, rootState}, {survey}) {

    const id = survey.id;
    const name = survey.name;

    const MUTATION_NAME = 'updateSurveyMutation';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $id:Int!,
                    $name:String!
                 ) {
                      ${MUTATION_NAME}(
                        id:$id,
                        name:$name
                      ) {
                        ${state.surveysQueryResponse}
                      }
                    }                           
                `,
            variables: {
              id: id,
              name: name,
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
            commit(TYPES.mutations.updateSurvey, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  deleteSurvey({commit, rootGetters, dispatch, rootState}, {survey}) {

    const id = survey.id;

    const MUTATION_NAME = 'deleteSurveyMutation';
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
                        ${state.surveysQueryResponse}
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
            commit(TYPES.mutations.deleteSurvey, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  }
};

const mutations = {
  [TYPES.mutations.setSurvey](state, payload) {
    state.surveys = payload;
  },
  //pagination
  [TYPES.mutations.getSurveys](state, payload) {
    state.surveysBasedOnPagination = payload;
    //state.surveys = payload;

    if (state.surveysBasedOnPaginationInitStateLength === 0) {
      state.surveysBasedOnPaginationInitStateLength = payload.filter(e => {
        return e;
      }).length;
    }
  },
  [TYPES.mutations.storeSurvey](state, payload) {
    state.surveysBasedOnPagination.unshift(payload)
    state.surveys = state.surveysBasedOnPagination;
  },
  [TYPES.mutations.updateSurvey](state, payload) {
    state.surveysBasedOnPagination.map(entry => {
      if (entry.id === payload.id) {
        entry.name = payload.name
      }
    });
    state.surveys.map(entry => {
      if (entry.id === payload.id) {
        entry.name = payload.name
      }
    });
  },
  [TYPES.mutations.deleteSurvey](state, payload) {
    state.surveysBasedOnPagination = state.surveysBasedOnPagination.filter(entry => entry.id !== payload.id);
    state.surveys = state.surveysBasedOnPagination;
  }
};
const getters = {
  surveyCollection: (state) => state.surveys,
  //related to pagination
  headers: (state) => state.headers,
  mobileHeaders: (state) => state.mobileHeaders,
  surveyCollectionBasedOnPagination: (state) => state.surveysBasedOnPagination.filter(e => {
    return e;
  }).map(item => ({
    ...item,
    id_name: `<b>${item.id}</b><br>${item.name || ''}`
  })),
  surveyCollectionBasedOnPaginationInitStateLength: (state) => state.surveysBasedOnPaginationInitStateLength
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
