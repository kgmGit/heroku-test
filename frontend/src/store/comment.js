import { Http as http } from "@/Services/Http";

const state = {
  isChange: false,
  timeOfLastComment: null,
};

const getters = {
  isChange(state) {
    return state.isChange;
  },
  timeOfLastComment(state) {
    return state.timeOfLastComment;
  },
};

const mutations = {
  setIsChange(state, value) {
    state.isChange = value;
  },
  setTimeOfLastComment(state, value) {
    state.timeOfLastComment = value;
  },
};

const actions = {
  ini({ commit }) {
    commit("setIsChange", false);
    commit("setTimeOfLastComment", null);
  },

  async get({ commit, getters }, roomName) {
    const timeOfLastComment = getters.timeOfLastComment;
    const queryParam = timeOfLastComment ? `?time=${timeOfLastComment}` : "";

    const response = await http.get(
      `/api/rooms/${roomName}/comments/${queryParam}`
    );

    const comments = response.data.data;
    if (comments.length > 0) {
      commit("setTimeOfLastComment", comments[comments.length - 1].created_at);
    }
    commit("setIsChange", false);

    return comments;
  },

  async post({ commit }, { roomName, content }) {
    const body = {
      content: content,
    };
    console.log("content: ", body);
    await http.post(`/api/rooms/${roomName}/comments`, body);
    commit("setIsChange", true);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
