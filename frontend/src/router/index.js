import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/Home.vue";
import Login from "@/views/Login.vue";
import Register from "@/views/Register.vue";
import ProfileUpdate from "@/views/ProfileUpdate.vue";
import ForgotPassword from "@/views/ForgotPassword.vue";
import ResetPassword from "@/views/ResetPassword.vue";
import Error from "@/views/Error.vue";
import RoomUpdate from "@/views/RoomUpdate.vue";
import ChatRoom from "@/views/ChatRoom.vue";

const routes = [
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
    path: "/room-update",
    name: "room-update",
    component: RoomUpdate,
  },
  {
    path: "/chat-room/:roomName",
    name: "chat-room",
    component: ChatRoom,
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

export default router;
