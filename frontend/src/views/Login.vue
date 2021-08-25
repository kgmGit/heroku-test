<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">ログイン</div>
    <div class="card-body">
      <form>
        <label for="email" class="form-label">Eメール</label>
        <input
          type="text"
          v-model="form.email"
          :class="{ 'is-invalid': errors && errors['email'] }"
          id="email"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['email']" />

        <label for="password" class="form-label mt-2">パスワード</label>
        <input
          type="password"
          v-model="form.password"
          :class="{ 'is-invalid': errors && errors['password'] }"
          id="password"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['password']" />

        <div class="float-end">
          <custom-button :onclick="submit" class="btn btn-primary mt-3"
            >ログイン</custom-button
          >
        </div>
        <div class="mt-2">
          <router-link to="/forgot-password"
            >パスワードを忘れた場合</router-link
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  computed: {
    ...mapGetters({
      errors: "auth/errorMessages",
      user: "auth/user",
    }),
  },
  methods: {
    async submit() {
      await this.$store.dispatch("auth/login", this.form);
      if (this.user) {
        this.$store.dispatch("message/setContent", "ログインしました");
        this.$router.replace({ name: "Home" });
      }
    },
  },
};
</script>
