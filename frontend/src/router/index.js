import { createRouter, createWebHistory } from "vue-router";
import store from "@/store";
import Home from "@/views/Home.vue";
import Login from "@/views/Login.vue";
import Register from "@/views/Register.vue";
import ProfileUpdate from "@/views/ProfileUpdate.vue";
import ForgotPassword from "@/views/ForgotPassword.vue";
import ResetPassword from "@/views/ResetPassword.vue";
import Error from "@/views/Error.vue";

const routes = [
  // {
  //   path: "/",
  //   name: "Home",
  //   component: Home,
  // },
  {
    path: "/login",
    name: "login",
    component: Login,
  },
  {
    path: "/register",
    name: "register",
    component: Register,
  },

  {
    path: "/profile-update",
    name: "profile-update",
    component: ProfileUpdate,
  },
  {
    path: "/forgot-password",
    name: "forgot-password",
    component: ForgotPassword,
  },
  {
    path: "/reset-password",
    name: "reset-password",
    component: ResetPassword,
  },
  {
    path: "/error",
    name: "error",
    component: Error,
  },
  {
    path: "/:pathMatch(.*)*",
    name: "Home",
    component: Home,
  },
];

const router = createRouter({
  history: createWebHistory("/"),
  routes,
});

router.beforeEach((to, from, next) => {
  if (store.getters["auth/isAuthError"]) {
    store.commit("auth/setIsAuthError", false);
    store.dispatch("message/setContent", null);

    next({ name: "error" });
  } else {
    next();
  }
});

export default router;
