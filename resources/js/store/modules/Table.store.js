import {
  RESET,

  SET_CURRENT_PAGE,
  SET_PER_PAGE,
  SET_LAST_PAGE,

  SET_SEARCH,
} from '../constants'

const initialState = () => ({
  search: null,
})

const state = {
  ...initialState(),
  current_page: 1, // in query
  per_page: 10, // in query
  last_page: 1 // update in response
}

const getters = {
  current_page: state => state.current_page,
  per_page: state => state.per_page,
  search: state => state.search,

  last_page: state => state.last_page,
}
const actions = {
  setPage({commit}, payload) {
    commit(SET_CURRENT_PAGE, payload)
  },
  rowsLoaded({commit}, payload) {
    const {last_page} = {...payload}

    commit(SET_LAST_PAGE, last_page)
  },
}
const mutations = {
  [SET_CURRENT_PAGE](state, payload) {
    state.current_page = payload
  },
  // not in used this one
  [SET_PER_PAGE](state, payload) {
    state.current_page = 1
    state.per_page = payload
  },
  [SET_LAST_PAGE](state, payload) {
    state.last_page = payload
  },


  [SET_SEARCH](state, payload) {
    state.search = payload
  },
  [RESET](state) {
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
