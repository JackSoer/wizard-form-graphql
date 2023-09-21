<template>
  <div class="login">
    <login-form />
  </div>
</template>
<script>
import LoginForm from "@/components/LoginForm.vue";

export default {
  components: { LoginForm },
};
</script>
<style lang="scss" scoped>
.login {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
