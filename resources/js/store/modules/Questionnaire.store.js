import {PAGINATION} from "../constants";

export const TYPES = {
  actions: {
    loadQuestionData: "loadQuestionData"
  },
  mutations: {
    setQuestion: "setQuestion",
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
      title: "Question",
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
  questionsInitStateLength: 0,
  questionsQueryResponse:
      `
    id,
    question,
    survey {
      id
      name
    },
    created_at
    `,
}

const actions = {
  getQuestionData({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const {search, orderBy} = payload;

    // async [TYPES.actions.loadClientInventoryData]({commit, rootGetters, dispatch, rootState, state}) {
    const QUERY_NAME = 'questionsQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `query ${QUERY_NAME} ($per_page:Int, $current_page:Int, $search:String, $orderBy:String){
          ${QUERY_NAME} (per_page:$per_page,current_page:$current_page,search:$search,orderBy:$orderBy){
             data{
                ${state.questionsQueryResponse}
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
            commit(TYPES.mutations.setQuestion, res['data']);
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
  }
};

const mutations = {
  [TYPES.mutations.setQuestion](state, payload) {
    state.questions = payload;

    if (state.questionsInitStateLength === 0) {
      state.questionsInitStateLength = payload.filter(e => {
        return e;
        //return moment().format('YYYY-MM-DD') === moment(e['created_at']).format('YYYY-MM-DD');
      }).length;
    }
  },

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
  questionCollectionInitStateLength: (state) => state.questionsInitStateLength
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
