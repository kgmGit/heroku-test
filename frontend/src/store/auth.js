import axios from 'axios';

export default {
  namespaced: true,

  state: {
    isAuth: false,
    user: null,
  },
  getters: {
    isAuth(state) {
      return state.isAuth;
    },
    user(state) {
      return state.user;
    },
  },
  mutations: {
    setIsAuth(state, value) {
      state.isAuth = value;
    },
    setUser(state, value) {
      state.user = value;
    },
  },
  actions: {
    async login({commit}, credentials) {
      await axios.get('/sanctum/csrf-cookie');
      let response = null;
      try {
        response = await axios.post('/login',credentials);
      } catch (e) {
        commit('setIsAuth', false);
        commit('setUser', null);
        return;
      }
      commit('setIsAuth', true);
      commit('setUser', response.data.data);
    },
    async logout({commit}) {
      await axios.post('/logout');
      commit('setIsAuth', false);
      commit('setUser', null);
    },
    async user() {
      const response = await axios.get('/api/user');
      return response;
    }
  }
}