const state = {
  content: null,
};

const getters = {
  content(state) {
    return state.content;
  },
};

const mutations = {
  setContent(state, value) {
    state.content = value;
  },
};

const actions = {
  setContent({ commit }, value) {
    commit("setContent", value);

    const timeout = 3000;
    setTimeout(() => {
      commit("setContent", null);
    }, timeout);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
