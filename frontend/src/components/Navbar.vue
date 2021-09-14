<template>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link to="/" class="nav-link">Home</router-link>
          </li>
          <li v-show="!isAuth" class="nav-item">
            <router-link to="/register" class="nav-link">登録</router-link>
          </li>
          <li v-show="!isAuth" class="nav-item">
            <router-link to="/login" class="nav-link">ログイン</router-link>
          </li>
          <li v-show="isAuth" class="nav-item">
            <a class="nav-link" href="#" @click.prevent="logout">ログアウト</a>
          </li>
          <li v-show="isAuth" class="nav-item">
            <router-link to="/profile-update" class="nav-link"
              >ユーザ情報更新</router-link
            >
          </li>
          <li v-show="isVerified" class="nav-item">
            <router-link to="/room-update" class="nav-link"
              >ルーム情報更新</router-link
            >
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters({
      isAuth: "auth/isAuth",
      isVerified: "auth/isVerified",
    }),
  },
  methods: {
    async logout() {
      await this.$store
        .dispatch("auth/logout")
        .then(() => {
          this.$store.dispatch("message/setContent", "ログアウトしました");
          this.$router.replace({ name: "Home" });
        })
        .catch(() => {
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>