<template>
  <div id="nav">
    <router-link to="/">Home</router-link> |
    <router-link to="/about">About</router-link> |
    <span v-show="!isAuth"><router-link to="/login">Login</router-link></span>
    <span v-show="isAuth"
      ><a href="#" @click.prevent="logoutEvt">Logout</a></span
    >
    <span><a href="#" @click.prevent="test">Test</a></span>
    <router-view />
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  computed: {
    ...mapGetters({
      isAuth: "auth/isAuth",
    }),
  },
  methods: {
    ...mapActions({
      logout: "auth/logout",
      user: "auth/user",
    }),
    async logoutEvt() {
      await this.logout();
      this.$router.replace({ name: "Home" });
    },
    async test() {
      await this.user();
    },
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
