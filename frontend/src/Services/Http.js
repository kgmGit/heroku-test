import axios from "axios";
import store from "@/store";

const isDev = process.env.NODE_ENV === "development";

export const Http = axios.create({
  withCredentials: true,
});

Http.interceptors.request.use((request) => {
  if (isDev) {
    console.log("*** request log start ***");
    console.log(request.method + " " + request.url);
    console.log("data: ", request.data);
    console.log("*** request log end ***");
  }

  return request;
});

Http.interceptors.response.use(
  (response) => {
    if (isDev) {
      console.log("*** response log start ***");
      console.log("status: ", response.status);
      console.log("data: ", response.data);
      console.log("*** response log end ***");
    }
    return response;
  },
  async (error) => {
    if (isDev) {
      const response = error.response;
      console.log("*** response log start ***");
      console.log("status: ", response.status);
      console.log("data: ", response.data);
      console.log("*** response log end ***");
    }
    if (
      error.response &&
      [401, 419].includes(error.response.status) &&
      store.getters["auth/user"]
    ) {
      await store.dispatch("auth/logout");
    }
    throw error;
  }
);
