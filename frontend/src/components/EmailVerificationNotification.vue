<template>
  <div>
    <custom-button :onclick="submit" class="btn btn-primary"
      >確認メール送信</custom-button
    >
  </div>
</template>

<script>
export default {
  methods: {
    async submit() {
      await this.$store.dispatch("auth/sendVerifyMail");
      this.$store.dispatch("message/setContent", "確認メールを送信しました");
    },
  },
};
</script>