import { Http as http } from "@/Services/Http";

const state = {
  isAuth: false,
  user: null,
  errorMessages: null,
  isAuthError: false,
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
  isAuthError(state) {
    return state.isAuthError;
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
  setIsAuthError(state, value) {
    state.isAuthError = value;
  },
};

const actions = {
  async register({ commit, dispatch }, credentials) {
    commit("setErrorMessages", null);

    await http.get("/sanctum/csrf-cookie");
    const response = await http
      .post("/register", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
    if (response.status === 201) {
      await dispatch("me");
    }
  },
  async login({ commit, dispatch }, credentials) {
    commit("setErrorMessages", null);

    await http.get("/sanctum/csrf-cookie");
    const response = await http
      .post("/login", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
    if (response.status === 200) {
      await dispatch("me");
    }
  },
  async logout({ commit }) {
    await http.get("/sanctum/csrf-cookie");
    await http.post("/logout").catch((e) => e.response);

    commit("setIsAuth", false);
    commit("setUser", null);
  },
  async me({ commit }) {
    const response = await http.get("/api/user").catch((e) => e.response);
    if (response.status === 200) {
      commit("setIsAuth", true);
      commit("setUser", response.data.data);
    } else {
      commit("setIsAuth", false);
      commit("setUser", null);
    }
  },
  async sendVerifyMail() {
    await http.post("/email/verification-notification");
  },
  async sendResetPasswordMail({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await http
      .post("/forgot-password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
  },
  async resetPassword({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await http
      .post("/reset-password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
  },
  async updateUser({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await http
      .put("/user/profile-information", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
  },
  async updatePassword({ commit }, credentials) {
    commit("setErrorMessages", null);

    const response = await http
      .put("/user/password", credentials)
      .catch((e) => e.response);

    if (response.status === 422) {
      commit("setErrorMessages", response.data.errors);
    }
  },
  async deleteUser({ commit }) {
    const response = await http.delete("/user").catch((e) => e.response);
    if (response.status === 204) {
      commit("setIsAuth", false);
      commit("setUser", null);
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
