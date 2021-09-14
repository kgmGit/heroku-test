<template>
  <div class="card h-100 overflow-auto">
    <div class="card-body">
      <div v-for="comment in comments" :key="comment.id" class="mb-2">
        <div class="row" :id="'comment-' + comment.id">
          <!-- 自コメント -->
          <div v-if="user.id === comment.user_id">
            <div class="col-sm-7 offset-5 d-flex justify-content-end">
              <div
                class="
                  border border-primary
                  bg-primary
                  rounded
                  p-3
                  text-white
                  comment
                "
              >
                {{ comment.content }}
              </div>
            </div>
          </div>
          <!-- 他コメント -->
          <div v-else>
            <div class="col-sm-7 d-flex justify-content-start">
              <div
                class="
                  border border-border-secondary
                  bg-secondary
                  rounded
                  p-3
                  text-white
                  comment
                "
              >
                <div>
                  {{ comment.user_name }}
                </div>
                <div>
                  {{ comment.content }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    comments: {
      type: Array,
    },
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },
  updated() {
    if (this.comments) {
      // 最新コメントまでスクロール
      const latestCommentElement = document.getElementById(
        `comment-${this.comments[this.comments.length - 1].id}`
      );
      latestCommentElement.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  },
};
</script>

<style scoped>
.comment {
  white-space: pre-wrap;
  white-space: pre-line;
}
</style>