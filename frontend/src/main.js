import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router";
import store from "@/store";
import validationErrors from "@/components/ValidationErrors.vue";
import customButton from "./components/CustomButton.vue";

import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

console.log("process.env", process.env);

console.log("VUE_APP_PUSHER_APP_KEY", process.env.VUE_APP_PUSHER_APP_KEY);
console.log("PUSHER_APP_KEY", process.env.PUSHER_APP_KEY);

window.Echo = new Echo({
  broadcaster: "pusher",
  key: process.env.VUE_APP_PUSHER_APP_KEY,
  cluster: process.env.VUE_APP_PUSHER_APP_CLUSTER,
  forceTLS: true,
});

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
