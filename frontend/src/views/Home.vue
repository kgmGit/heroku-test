<template>
  <div class="mx-auto col-sm-6">
    <!-- 未ログイン -->
    <div v-if="!isAuth">
      <div class="card">
        <div class="card-header">ログインしてください</div>
        <div class="card-body text-center">
          <router-link to="/login" class="btn btn-primary"
            >ログイン</router-link
          >
          <div>新規登録は<router-link to="/register">こちら</router-link></div>
        </div>
      </div>
    </div>
    <!-- 未メール確認 -->
    <div v-else-if="!isVerified">
      <email-verification-notification />
    </div>
    <!-- メール確認済み -->
    <div v-else-if="isVerified">
      <rooms />
    </div>
  </div>
</template>

<script>
import EmailVerificationNotification from "@/components/EmailVerificationNotification.vue";
import Rooms from "@/components/Rooms";

import { mapGetters } from "vuex";

export default {
  components: {
    EmailVerificationNotification,
    Rooms,
  },
  computed: {
    ...mapGetters({
      isAuth: "auth/isAuth",
      isVerified: "auth/isVerified",
    }),
  },
};
</script>
