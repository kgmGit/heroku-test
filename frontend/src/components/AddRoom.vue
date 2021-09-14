<template>
  <div class="card">
    <div class="card-header">ルーム作成</div>
    <div class="card-body">
      <form autocomplete="no">
        <label for="room-name" class="form-label">ルーム名</label>
        <input
          id="room-name"
          type="text"
          v-model="name"
          :class="{ 'is-invalid': errors && errors['name'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['name']" />

        <label for="password" class="form-label mt-2">パスワード</label>

        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="yes-no"
            id="no"
            value="no"
            checked
            v-model="passwordYesNo"
          />
          <label class="form-check-label" for="no">なし</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="yes-no"
            id="yes"
            value="yes"
            v-model="passwordYesNo"
          />
          <label class="form-check-label" for="yes">あり</label>
        </div>

        <input
          type="password"
          v-model="password"
          :class="{ 'is-invalid': errors && errors['password'] }"
          id="password"
          class="form-control"
          :disabled="passwordYesNo === 'no'"
        />
        <validation-errors :errors="errors && errors['password']" />

        <div class="float-end">
          <custom-button :onclick="add" class="btn btn-primary mt-3"
            >作成</custom-button
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
      name: null,
      passwordYesNo: "no",
      password: "",
      errors: null,
    };
  },
  methods: {
    async add() {
      this.errors = null;

      const password = this.passwordYesNo === "yes" ? this.password : null;

      await this.$store
        .dispatch("room/add", { name: this.name, password: password })
        .then(() => {
          this.$store.dispatch("message/setContent", "ルームを作成しました");
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
