import { Http as http } from "@/Services/Http";

const state = {};

const getters = {};

const mutations = {};

const actions = {
  async get(context, roomName) {
    const response = await http.get(`/api/rooms/${roomName}/members`);
    const users = response.data.data;
    return users;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
