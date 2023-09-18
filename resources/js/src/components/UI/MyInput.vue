<template>
  <div class="my-input">
    <label class="my-input__label" :for="$attrs.id">{{ label }}</label>
    <input
      :type="type"
      class="my-input__item"
      @input="change"
      v-bind="$attrs"
      :value="modelValue"
    />
    <p class="error" v-for="error in errors" :key="error.$uid">
      {{ error.$message }}
    </p>
  </div>
</template>
<script>
export default {
  name: "my-input",
  inheritAttrs: false,
  props: {
    type: {
      type: String,
      default: "text",
    },
    modelValue: {
      type: [String],
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    errors: {
      type: [Array],
      default: [],
    },
  },
  methods: {
    change(e) {
      this.$emit("update:modelValue", e.target.value, e.target.name);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";

.my-input {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;

  &__item {
    box-shadow: 0 0 5px 3px $mainBoxShadow;
    background-color: white;
    outline: none;
    padding: 5px;
    font-size: 18px;
    width: 100%;

    &:focus {
      outline: 1px solid rgb(0, 183, 255);
    }
  }
}
</style>
