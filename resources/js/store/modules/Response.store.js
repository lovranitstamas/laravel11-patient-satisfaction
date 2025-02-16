import {PAGINATION} from "../constants";

export const TYPES = {
  mutations: {
    getResponse: "getResponse"
  },
};

const initialState = () => ({
  mobileHeaders: [
    {
      title: "ID<br>Kérdés",
      value: "id_response",
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
      width: '5%'
    },
    {
      title: "Beküldő neve",
      value: "submitter_name",
      align: 'center',
      sortable: false,
      width: '10%'
    },
    {
      title: "E-mail cím",
      value: "email",
      align: 'center',
      sortable: false,
      width: '10%'
    },
    {
      title: "Kérdőív",
      value: "survey.name",
      align: 'center',
      sortable: false,
      width: '20%'
    },
    {
      title: "Kérdés",
      value: "question.question",
      align: 'center',
      sortable: false,
      width: '25%'
    },
    {
      title: "Válasz",
      value: "response",
      align: 'center',
      sortable: false,
      width: '30%'
    },
  ],
})

const state = {
  ...initialState(),
  responses: [],
  responsesInitStateLength: 0,
  responseResponse:
      `
    id,
    submitter_name,
    email,
    survey_id,
    question_id,
    survey {
      id
      name,
    }, 
    question {
      id
      question
    },
    response,
    created_at
    `
}

const actions = {
  getResponseData({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const {search, orderBy} = payload;

    const QUERY_NAME = 'responsesQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `query ${QUERY_NAME} ($per_page:Int, $current_page:Int, $search:String, $orderBy:String){
          ${QUERY_NAME} (per_page:$per_page,current_page:$current_page,search:$search,orderBy:$orderBy){
             data{
                ${state.responseResponse}
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
            commit(TYPES.mutations.getResponse, res['data']);
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
};

const mutations = {
  [TYPES.mutations.getResponse](state, payload) {
    state.responses = payload;

    if (state.responsesInitStateLength === 0) {
      state.responsesInitStateLength = payload.filter(e => {
        return e;
      }).length;
    }
  }
};
const getters = {
  headers: (state) => state.headers,
  mobileHeaders: (state) => state.mobileHeaders,
  responseCollection: (state) => state.responses.filter(e => {
    return e;
  }).map(item => ({
    ...item,
    id_response: `<b>${item.id}</b><br/>${item.response || ''}`
  })),
  responseCollectionInitStateLength: (state) => state.responsesInitStateLength
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
