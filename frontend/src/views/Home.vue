<template>
  <div>
    <p>Home</p>
    <!-- 未ログイン -->
    <div v-if="!isAuth">
      <router-link to="/login" class="btn btn-primary">ログイン</router-link>
      <div>新規登録は<router-link to="/register">こちら</router-link></div>
    </div>
    <!-- 未メール確認 -->
    <email-verification-notification
      v-else-if="!isVerified"
    ></email-verification-notification>
    <!-- メール確認済み -->
    <div v-else-if="isVerified">
      <p>ルーム一覧</p>
    </div>
  </div>
</template>

<script>
import EmailVerificationNotification from "@/components/EmailVerificationNotification.vue";
import { mapGetters } from "vuex";

export default {
  components: {
    EmailVerificationNotification,
  },
  computed: {
    ...mapGetters({
      isAuth: "auth/isAuth",
      isVerified: "auth/isVerified",
    }),
  },
};
</script>
