<template>
  <button
    class="my-button"
    :class="{ 'my-button--disabled': isLoading }"
    :disabled="isLoading"
  >
    <slot v-if="!isLoading"></slot>
    <div v-if="isLoading" class="loading-box"><loading /></div>
  </button>
</template>
<script>
import Loading from "../Loading.vue";

export default {
  components: { Loading },
  name: "my-button",
  props: {
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";

.my-button {
  background-color: $mainBackground;
  color: $mainTextColor;
  font-size: 20px;
  height: 55px;
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

  &--disabled {
    opacity: 0.5;
    cursor: default;

    &:hover {
      background-color: $mainBackground;
      color: $mainTextColor;
    }
  }
}

.loading-box {
  width: 100%;
  height: 100%;
}
</style>
