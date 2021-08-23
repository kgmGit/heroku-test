<template>
  <div>
    <user-update />
    <password-update class="mt-2" />
    <delete-user class="mt-2" />
  </div>
</template>

<script>
import UserUpdate from "@/components/UserUpdate.vue";
import PasswordUpdate from "@/components/PasswordUpdate.vue";
import DeleteUser from "@/components/DeleteUser.vue";

export default {
  components: {
    UserUpdate,
    PasswordUpdate,
    DeleteUser,
  },
};
</script>