import { Http as http } from "@/Services/Http";

const state = {
  isChange: false,
};

const getters = {
  isChange(state) {
    return state.isChange;
  },
};

const mutations = {
  setIsChange(state, value) {
    state.isChange = value;
  },
};

const actions = {
  async getRooms({ commit }, user_id = null) {
    const queryParam = user_id ? `?user-id=${user_id}` : "";

    const response = await http.get(`/api/rooms${queryParam}`);
    commit("setIsChange", false);

    return response.data.data;
  },

  async join(context, { roomName, password }) {
    const body = {};

    if (password) {
      body.password = password;
    }

    await http.put(`/api/rooms/${roomName}/members`, body);
  },

  async add({ commit }, { name, password }) {
    const body = {
      name: name,
    };

    if (password !== null) {
      body.password = password;
    }

    await http.post(`/api/rooms`, body);
    commit("setIsChange", true);
  },

  async update({ commit }, { preName, updateName }) {
    const body = {
      name: updateName,
    };

    await http.patch(`/api/rooms/${preName}`, body);
    commit("setIsChange", true);
  },

  async delete({ commit }, roomName) {
    await http.delete(`/api/rooms/${roomName}`);
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
