<template>
  <div class="row pb-5 px-5 window-height">
    <div class="col-8 h-100">
      <comments :comments="comments" />
    </div>
    <div class="col-4 h-100">
      <div class="users-height">
        <users :users="users" />
      </div>
      <div class="chat-form-height">
        <chat-form />
      </div>
    </div>
  </div>
</template>

<script>
import Comments from "@/components/ChatRoom/Comments.vue";
import Users from "@/components/ChatRoom/Users.vue";
import ChatForm from "@/components/ChatRoom/ChatForm.vue";

export default {
  components: {
    Comments,
    Users,
    ChatForm,
  },
  data() {
    return {
      comments: [],
      users: null,
    };
  },
  methods: {
    async getComments() {
      this.comments = await this.$store.dispatch(
        "comment/get",
        this.$route.params.roomName
      );
    },
    async getUsers() {
      this.users = await this.$store.dispatch(
        "user/get",
        this.$route.params.roomName
      );
    },
  },
  async created() {
    try {
      await this.getComments();
      await this.getUsers();
    } catch (e) {
      this.$router.replace({ name: "error" });
    }

    window.Echo.private(`chat-room-${this.$route.params.roomName}`).listen(
      "CommentPosted",
      async (response) => {
        this.comments.push(response.comment);
        if (!this.users.find((user) => user.id === response.comment.user_id)) {
          await this.getUsers();
        }
      }
    );
  },
};
</script>

<style scoped>
.window-height {
  height: calc(100vh - 100px);
}
.users-height {
  height: 70%;
}
.chat-form-height {
  height: 30%;
}
</style>