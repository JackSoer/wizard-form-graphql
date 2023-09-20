<template>
  <div class="loading">
    <img
      src="../assets/images/loading.svg"
      alt="loading"
      class="loading__icon"
    />
  </div>
</template>
<script>
export default {};
</script>
<style lang="scss" scoped>
.loading {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  &__icon {
    max-width: 100%;
    max-height: 100%;
  }
}
</style>
