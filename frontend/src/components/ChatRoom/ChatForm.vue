<template>
  <div class="card h-100">
    <div class="card-body">
      <form>
        <textarea
          v-model="content"
          class="form-control"
          id="content"
          :class="{ 'is-invalid': errors && errors['content'] }"
        ></textarea>
        <validation-errors :errors="errors && errors['content']" />
        <div class="float-end">
          <custom-button :onclick="submit" class="btn btn-primary mt-3"
            >送信</custom-button
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
      content: null,
      errors: null,
    };
  },
  methods: {
    async submit() {
      this.errors = null;

      await this.$store
        .dispatch("comment/post", {
          roomName: this.$route.params.roomName,
          content: this.content,
        })
        .then(() => {
          this.content = null;
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

<style scoped>
textarea {
  resize: none;
}
</style>