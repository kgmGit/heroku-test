<template>
  <div class="p-2 border">
    <form @submit.prevent="" class="d-flex">
      <div class="flex-fill">
        <input
          type="text"
          v-model="updateName"
          :class="{ 'is-invalid': errors && errors['name'] }"
          class="form-control"
        />
        <validation-errors :errors="errors && errors['name']" />
      </div>

      <custom-button :onclick="update" class="mx-2 btn btn-primary"
        >変更</custom-button
      >
      <custom-button :onclick="destroy" class="btn btn-primary"
        >削除</custom-button
      >
    </form>
  </div>
</template>

<script>
export default {
  props: {
    room: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      updateName: this.room.name,
      errors: null,
    };
  },
  methods: {
    async update() {
      this.errors = null;

      this.$store
        .dispatch("room/update", {
          preName: this.room.name,
          updateName: this.updateName,
        })
        .then(() => {
          alert("ルーム名を更新しました");
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
            return;
          }
          this.$router.replace({ name: "error" });
        });
    },
    async destroy() {
      this.$store
        .dispatch("room/delete", this.room.name)
        .then(() => {
          alert("ルームを削除しました");
        })
        .catch(() => {
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>

