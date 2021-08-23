<template>
  <div>
    <header>
      <navbar />
    </header>
    <main style="padding-top: 100px">
      <div class="container-fluid">
        <message />
        <router-view />
      </div>
    </main>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import Message from "@/components/Message.vue";

export default {
  components: {
    Navbar,
    Message,
  },
};
</script>

