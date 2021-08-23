<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">パスワード更新</div>
    <div class="card-body">
      <form>
        <label for="current-password" class="form-label"
          >現在のパスワード</label
        >
        <input
          type="password"
          v-model="form.current_password"
          :class="{ 'is-invalid': errors && errors['current_password'] }"
          id="current-password"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['current_password']" />

        <label for="password" class="form-label mt-2">新しいパスワード</label>
        <input
          type="password"
          v-model="form.password"
          :class="{ 'is-invalid': errors && errors['password'] }"
          id="password"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['password']" />

        <label for="password-confirmation" class="form-label mt-2"
          >新しいパスワード（確認）</label
        >
        <input
          type="password"
          v-model="form.password_confirmation"
          :class="{
            'is-invalid': errors && errors['password_confirmation'],
          }"
          id="password-confirmation"
          class="form-control"
        />
        <validation-errors
          :errors="errors && errors['password_confirmation']"
        />

        <div class="d-flex justify-content-end">
          <custom-button :onclick="submit" class="btn btn-primary mt-3"
            >更新</custom-button
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
        current_password: null,
        password: null,
        password_confirmation: null,
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
      await this.$store.dispatch("auth/updatePassword", this.form);
      if (!this.errors) {
        this.$store.dispatch("message/setContent", "パスワードを更新しました");
        this.$router.replace({ name: "Home" });
      }
    },
  },
};
</script>
