import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router";
import store from "@/store";
import validationErrors from "@/components/ValidationErrors.vue";
import customButton from "./components/CustomButton.vue";

import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";

const createApplication = async () => {
  await store.dispatch("auth/me");

  createApp(App)
    .use(store)
    .use(router)
    .component("validationErrors", validationErrors)
    .component("customButton", customButton)
    .mount("#app");
};

createApplication();
