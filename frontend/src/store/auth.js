import axios from "axios";

const state = {
  isAuth: false,
  user: null,
  errorMessages: null,
};

const getters = {
  isAuth(state) {
    return state.isAuth;
  },
  isVerified(state) {
    return !!state.user && !!state.user.email_verified_at;
  },
  user(state) {
    return state.user;
  },
  errorMessages(state) {
    return state.errorMessages;
  },
};

const mutations = {
  setIsAuth(state, value) {
    state.isAuth = value;
  },
  setUser(state, value) {
    state.user = value;
  },
  setErrorMessages(state, value) {
    state.errorMessages = value;
  },
};

const actions = {
  async register({ commit, dispatch }, credentials) {
    commit("setErrorMessages", null);

    await axios.get("/sanctum/csrf-cookie");
    const response = await axios
      .post("/register", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
    if (response.status === 201) {
      await dispatch("me");
      return;
    }
  },
  async login({ commit, dispatch }, credentials) {
    commit("setErrorMessages", null);

    await axios.get("/sanctum/csrf-cookie");
    const response = await axios
      .post("/login", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
    if (response.status === 200) {
      await dispatch("me");
      return;
    }
  },
  async logout({ commit }) {
    await axios.get("/sanctum/csrf-cookie");
    const response = await axios.post("/logout").catch((e) => e.response);

    if (response.status === 204) {
      commit("setIsAuth", false);
      commit("setUser", null);
      return;
    }
  },
  async me({ commit }) {
    const response = await axios.get("/api/user").catch((e) => e.response);
    if (response.status === 200) {
      commit("setIsAuth", true);
      commit("setUser", response.data.data);
    } else {
      commit("setIsAuth", false);
      commit("setUser", null);
    }
  },
  async sendVerifyMail() {
    await axios.post("/email/verification-notification");
  },
  async sendResetPasswordMail({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await axios
      .post("/forgot-password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
  },
  async resetPassword({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await axios
      .post("/reset-password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
  },
  async updateUser({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await axios
      .put("/user/profile-information", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
  },
  async updatePassword({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await axios
      .put("/user/password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
      return;
    }
  },
  async deleteUser({ commit }) {
    const response = await axios.delete("/user").catch((e) => e.response);
    if (response.status === 204) {
      commit("setIsAuth", false);
      commit("setUser", null);
      return;
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
