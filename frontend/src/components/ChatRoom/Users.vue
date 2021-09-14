<template>
  <div class="card h-100 overflow-auto">
    <div class="card-body">
      <ul class="list-group">
        <li v-for="user in users" :key="user.id" class="list-group-item">
          {{ user.name }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    users: {
      type: Array,
    },
  },
};
</script>