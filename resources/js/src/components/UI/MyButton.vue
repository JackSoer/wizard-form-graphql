<template>
  <button class="my-button">
    <slot></slot>
  </button>
</template>
<script>
export default {
  name: "my-button",
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";

.my-button {
  background-color: $mainBackground;
  color: $mainTextColor;
  font-size: 20px;
  padding: 12px 0;
  max-width: 100px;
  width: 100%;
  border-radius: 13px;
  font-weight: 500;
  border: 2px solid $mainTextColor;
  transition: all 0.3s ease;

  &:hover {
    background-color: $mainTextColor;
    color: white;
  }
}
</style>
