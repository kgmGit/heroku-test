<template>
  <div class="invalid-feedback">
    <div v-for="(item, index) in errors" :key="'validationError' + index">
      {{ item }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    errors: Array,
  },
};
</script>