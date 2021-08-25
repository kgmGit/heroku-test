<template>
  <div class="card mx-auto col-sm-6">
    <div class="card-header">エラー</div>
    <div class="card-body">
      <div>ログインし直してください。</div>
      <div class="float-end">
        <router-link to="/login" class="btn btn-primary">ログイン</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["status"],
};
</script>
