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
          <custom-button :onclick="submit" class="btn btn-primary mt-3">
            送信
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
      await this.$store.dispatch("auth/sendResetPasswordMail", this.form);
      if (!this.errors) {
        this.$store.dispatch("message/setContent", "メールを送信しました");
      }
    },
  },
};
</script>
