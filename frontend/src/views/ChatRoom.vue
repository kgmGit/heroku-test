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
      intervalId: null,
      latestCommentId: null,
    };
  },
  methods: {
    async getComments() {
      const newComments = await this.$store.dispatch(
        "comment/get",
        this.$route.params.roomName
      );

      if (newComments.length > 0) {
        this.comments = this.comments.concat(newComments);

        this.latestCommentId = newComments[newComments.length - 1].id;
      }
    },
    async getUsers() {
      this.users = await this.$store.dispatch(
        "user/get",
        this.$route.params.roomName
      );
    },
  },
  async created() {
    this.$store.dispatch("comment/ini");
    try {
      await this.getComments();
      await this.getUsers();
    } catch (e) {
      this.$router.replace({ name: "error" });
    }

    if (!process.env.VUE_APP_TIME_REFETCH_COMMENTS) {
      console.error("環境変数が読み込めませんでした");
      this.$router.replace({ name: "error" });
    }
    this.intervalId = setInterval(() => {
      try {
        this.getComments();
        this.getUsers();
      } catch (e) {
        this.$router.replace({ name: "error" });
      }
    }, process.env.VUE_APP_TIME_REFETCH_COMMENTS);
  },
  beforeUnmount() {
    clearInterval(this.intervalId);
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