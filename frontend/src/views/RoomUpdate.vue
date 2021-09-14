<template>
  <div class="mx-auto col-sm-6">
    <add-room />
    <update-rooms class="mt-2" />
  </div>
</template>

<script>
import AddRoom from "@/components/AddRoom.vue";
import UpdateRooms from "@/components/UpdateRooms.vue";

export default {
  components: {
    AddRoom,
    UpdateRooms,
  },
};
</script>