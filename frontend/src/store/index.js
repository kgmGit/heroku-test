import { createStore } from "vuex";
import auth from "@/store/auth";
import message from "@/store/message";

export default createStore({
  strict: true,
  modules: {
    auth,
    message,
  },
});
