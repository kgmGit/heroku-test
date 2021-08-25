import axios from "axios";
import store from "@/store";

export const Http = axios.create({
  withCredentials: true,
});

Http.interceptors.response.use(
  (response) => response,
  (error) => {
    if (
      error.response &&
      [401, 419].includes(error.response.status) &&
      store.getters["auth/user"]
    ) {
      store.dispatch("auth/logout");
      store.commit("auth/setIsAuthError", true);
    }
    return error;
  }
);
