import { createStore } from "vuex";
import auth from "@/store/auth";
import message from "@/store/message";
import room from "@/store/room";
import comment from "@/store/comment";
import user from "@/store/user";

export default createStore({
  strict: true,
  modules: {
    auth,
    message,
    room,
    comment,
    user,
  },
});
