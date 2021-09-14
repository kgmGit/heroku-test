<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">アカウントの削除</div>
    <div class="card-body">
      <div class="float-end">
        <custom-button :onclick="deleteUser" class="btn btn-primary mt-3">
          削除
        </custom-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      errors: null,
    };
  },
  methods: {
    async deleteUser() {
      this.errors = null;

      if (!confirm("アカウントを削除しますか？")) return;

      await this.$store
        .dispatch("auth/deleteUser")
        .then(() => {
          this.$store.dispatch(
            "message/setContent",
            "アカウントを削除しました"
          );
          this.$router.replace({ name: "Home" });
        })
        .catch(() => {
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>
