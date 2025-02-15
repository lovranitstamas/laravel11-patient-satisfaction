export const TYPES = {
  mutations: {
    setSurvey: "setSurvey"
  },
};

const initialState = () => ({
  surveys: [],
  surveyResponse:
    `
    id,
    name
    `
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
                   ${state.surveyResponse}
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
};

const mutations = {
  [TYPES.mutations.setSurvey](state, payload) {
    state.surveys = payload;
  }
};
const getters = {
  surveyCollection: (state) => state.surveys
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
