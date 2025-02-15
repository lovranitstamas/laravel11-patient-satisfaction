import {createStore} from 'vuex';

import {SET_SNACKBAR, TOGGLE_SNACKBAR} from "./constants";
import modules from "./modules";

export default createStore({
  modules,
  state: {
    snackbar: {
      show: false,
      messages: [],
      color: "red",
    },
    blade: 0,
  },
  mutations: {
    [SET_SNACKBAR](
        state,
        {
          show = state.snackbar.show,
          messages = state.snackbar.messages,
          color = state.snackbar.color,
        }
    ) {
      state.snackbar = {
        show: show,
        messages: messages,
        color: color,
      };
    },
    [TOGGLE_SNACKBAR](state, { show, messages = state.snackbar.messages, color = state.snackbar.color } = {}) {
      state.snackbar = {
        show: show,
        messages: messages,
        color: color,
      };
    }
  },
  getters: {
    snackbar: (state) => state.snackbar
  }
});
