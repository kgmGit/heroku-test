<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">パスワードリセットメールの送信</div>
    <div class="card-body">
      <form @submit.prevent="submit">
        <label for="email" class="form-label">Eメール</label>
        <input
          type="text"
          v-model="form.email"
          :class="{ 'is-invalid': errors && errors['email'] }"
          id="email"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['email']" />

        <div class="float-end">
          <custom-button :onclick="sendMail" class="btn btn-primary mt-3">
            送信
          </custom-button>
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
        email: "",
      },
      errors: null,
    };
  },
  methods: {
    async sendMail() {
      this.errors = null;

      await this.$store
        .dispatch("auth/sendResetPasswordMail", this.form)
        .then(() => {
          this.$store.dispatch("message/setContent", "メールを送信しました");
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
