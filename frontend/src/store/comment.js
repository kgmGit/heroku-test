import { Http as http } from "@/Services/Http";

const actions = {
  async get(context, roomName) {
    const response = await http.get(`/api/rooms/${roomName}/comments`);

    const comments = response.data.data;

    return comments;
  },

  async post(context, { roomName, content }) {
    const body = {
      content: content,
    };
    await http.post(`/api/rooms/${roomName}/comments`, body);
  },
};

export default {
  namespaced: true,
  actions,
};
