<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">パスワードのリセット</div>
    <div class="card-body">
      <form @submit.prevent="submit">
        <label for="email" class="form-label">Eメール</label>
        <input
          id="email"
          type="text"
          v-model="form.email"
          :class="{ 'is-invalid': errors && errors['email'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['email']" />

        <label for="password" class="form-label mt-2">パスワード</label>
        <input
          id="password"
          type="password"
          v-model="form.password"
          :class="{ 'is-invalid': errors && errors['password'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['password']" />

        <label for="password-confirmation" class="form-label mt-2"
          >パスワード（確認）</label
        >
        <input
          id="password-confirmation"
          type="password"
          v-model="form.password_confirmation"
          class="form-control"
        />

        <div class="float-end">
          <custom-button :onclick="submit" class="btn btn-primary mt-3">
            パスワードリセット
          </custom-button>
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
        password_confirmation: "",
      },
    };
  },
  computed: {
    ...mapGetters({
      errors: "auth/errorMessages",
    }),
  },
  methods: {
    async submit() {
      const data = {
        email: this.form.email,
        password: this.form.password,
        password_confirmation: this.form.password_confirmation,
        token: this.$route.query.token,
      };
      await this.$store.dispatch("auth/resetPassword", data);

      if (!this.errors) {
        this.$router.replace({ name: "Home" });
      }
    },
  },
};
</script>
