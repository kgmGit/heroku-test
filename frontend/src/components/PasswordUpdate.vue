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
          <custom-button :onclick="update" class="btn btn-primary mt-3"
            >更新</custom-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        current_password: null,
        password: null,
        password_confirmation: null,
      },
      errors: null,
    };
  },
  methods: {
    async update() {
      this.errors = null;

      await this.$store
        .dispatch("auth/updatePassword", this.form)
        .then(() => {
          this.$store.dispatch(
            "message/setContent",
            "パスワードを更新しました"
          );
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
