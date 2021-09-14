<template>
  <div class="card">
    <div class="card-header">ルーム編集</div>
    <div class="card-body">
      <div v-for="room in rooms" :key="room.id">
        <update-room class="mb-2" :room="room" />
      </div>
    </div>
  </div>
</template>

<script>
import UpdateRoom from "@/components/UpdateRoom";
import { mapGetters } from "vuex";

export default {
  components: {
    UpdateRoom,
  },
  data() {
    return {
      rooms: null,
    };
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
      isRoomChange: "room/isChange",
    }),
  },
  methods: {
    async getRooms() {
      this.rooms = await this.$store
        .dispatch("room/getRooms", this.user.id)
        .catch(() => {
          this.$router.replace({ name: "error" });
        });
    },
  },
  async created() {
    await this.getRooms();
  },
  watch: {
    async isRoomChange(val) {
      val && (await this.getRooms());
    },
  },
};
</script>