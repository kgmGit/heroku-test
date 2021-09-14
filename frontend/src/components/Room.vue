<template>
  <div class="p-2 border d-flex justify-content-between align-items-center">
    <div>{{ room.name }}</div>
    <div class="d-flex align-items-center">
      <div class="mx-4" v-if="room.locked">🔑</div>
      <custom-button :onclick="submit" class="btn btn-primary"
        >参加</custom-button
      >
    </div>
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
  methods: {
    async submit() {
      let password = "";
      if (this.room.locked) {
        password = prompt("パスワード");
      }
      if (password === null) {
        return;
      }

      this.$store
        .dispatch("room/join", { roomName: this.room.name, password: password })
        .then(() => {
          this.$router.push({
            name: "chat-room",
            params: { roomName: this.room.name },
          });
        })
        .catch((error) => {
          if (error.response.status === 422) {
            alert("パスワードが違います");
            return;
          }
          this.$router.replace({ name: "error" });
        });
    },
  },
};
</script>

