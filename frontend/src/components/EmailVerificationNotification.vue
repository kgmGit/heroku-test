<template>
  <div class="card">
    <div class="card-header">メール認証を完了してください</div>
    <div class="card-body text-center">
      <custom-button :onclick="submit" class="btn btn-primary"
        >確認メール送信</custom-button
      >
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    async submit() {
      await this.$store
        .dispatch("auth/sendVerifyMail")
        .then(() => {
          this.$store.dispatch(
            "message/setContent",
            "確認メールを送信しました"
          );
        })
        .catch(() => {
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>