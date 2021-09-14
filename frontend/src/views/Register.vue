<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">登録</div>
    <div class="card-body">
      <form>
        <label for="name" class="form-label">名前</label>
        <input
          id="name"
          type="text"
          v-model="form.name"
          :class="{ 'is-invalid': errors && errors['name'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['name']" />
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
          <custom-button :onclick="register" class="btn btn-primary mt-3"
            >登録</custom-button
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
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: null,
    };
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },
  methods: {
    async register() {
      this.errors = null;

      await this.$store
        .dispatch("auth/register", this.form)
        .then(() => {
          this.$store.dispatch("message/setContent", "登録しました");
          this.$router.replace({ name: "Home" });
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
            return;
          }
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>
