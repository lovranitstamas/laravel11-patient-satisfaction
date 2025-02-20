import {PAGINATION} from "../constants";

export const TYPES = {
  mutations: {
    getResponse: "getResponse"
  },
};

const initialState = () => ({
  mobileHeaders: [
    {
      title: "Kérdés<br>Válasz",
      value: "question_response",
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
      width: '15%'
    },
    {
      title: "Nem",
      value: "gender",
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
      title: "Kérdés",
      value: "question.question",
      align: 'center',
      sortable: false,
      width: '30%'
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
    gender
    email,
    question {
      id
      question,
      survey {
          id
          name,
      }
    },
    response,
    created_at
    `,
  userResponses:
      `
    id,
    submitter_name,
    email,
    created_at
    `
}

const actions = {
  //user side
  storeResponseData({commit, rootGetters, dispatch, rootState}, {userResponses}) {

    const submitter_name = userResponses.submitter_name || null;
    const gender = userResponses.gender || null;
    const email = userResponses.email || null;

    const answersArray = Object.entries(userResponses.answers).map(([question_id, response]) => [question_id, response]);

    const MUTATION_NAME = 'storeResponseMutation';

    return new Promise(async (resolve, reject) => {
      await axios.post(
          `${window.domainHttps}/graphql`, {
            query:
                `mutation ${MUTATION_NAME}(
                    $submitter_name:String,
                    $gender:String,
                    $email:String,
                    $answers: [[String]]  
                 ) {
                      ${MUTATION_NAME}(
                        submitter_name:$submitter_name,
                        gender:$gender,
                        email:$email,
                        answers: $answers 
                      ) {
                        ${state.userResponses}
                      }
                    }                           
                `,
            variables: {
              submitter_name: submitter_name,
              gender: gender,
              email: email,
              answers: answersArray
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
            console.log(res);
            //commit(TYPES.mutations.storeQuestion, res);
            resolve();
          }).catch((err) => {
            reject(err);
          });
    })
  },
  //admin side
  getResponseData({commit, rootGetters, dispatch, rootState}, payload = {}) {
    const {search, orderBy, surveyId} = payload;

    const QUERY_NAME = 'responsesQuery';
    return new Promise(async (resolve, reject) => {
      await axios.post(
          // return Axios.post(
          `${window.domainHttps}/graphql`, {
            query: `query ${QUERY_NAME} ($survey_id:Int, $per_page:Int, $current_page:Int, $search:String, $orderBy:String){
          ${QUERY_NAME} (survey_id:$survey_id, per_page:$per_page,current_page:$current_page,search:$search,orderBy:$orderBy){
             data{
                ${state.responseResponse}
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
    question_response: `<b>${item.question.question}</b><br/>${item.response || ''}`
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
