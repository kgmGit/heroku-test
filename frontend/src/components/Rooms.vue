<template>
  <div class="card">
    <div class="card-header">ルーム一覧</div>
    <div class="card-body">
      <div v-for="room in rooms" :key="room.id">
        <room class="mb-2" :room="room" />
      </div>
    </div>
  </div>
</template>

<script>
import Room from "@/components/Room";

export default {
  data() {
    return {
      rooms: null,
    };
  },
  components: {
    Room,
  },
  async created() {
    this.rooms = await this.$store.dispatch("room/getRooms").catch(() => {
      this.$router.replace({ name: "error" });
    });
  },
};
</script>