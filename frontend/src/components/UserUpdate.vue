<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">ユーザ情報更新</div>
    <div class="card-body">
      <form @submit.prevent="submit">
        <label for="name" class="form-label">名前</label>
        <input
          id="name"
          type="text"
          v-model="form.name"
          :class="{ 'is-invalid': errors && errors['name'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['name']" />

        <label for="email" class="form-label mt-2">Eメール</label>
        <input
          id="email"
          type="text"
          v-model="form.email"
          :class="{ 'is-invalid': errors && errors['email'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['email']" />

        <div class="float-end">
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
        name: null,
        email: null,
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
      await this.$store.dispatch("auth/updateUser", this.form);
      if (!this.errors) {
        this.$store.dispatch("message/setContent", "ユーザ情報を更新しました");
        this.$router.replace({ name: "Home" });
      }
    },
  },
  created() {
    (this.form.name = this.user.name), (this.form.email = this.user.email);
  },
};
</script>
