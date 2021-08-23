<template>
  <div v-show="message">
    <div class="alert alert-primary text-center" role="alert">
      {{ message }}
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters({
      message: "message/content",
    }),
  },
};
</script>