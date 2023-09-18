<template>
  <div class="textarea-box">
    <label :for="$attrs.id" class="textarea-label">{{ label }}</label>
    <textarea
      v-bind="$attrs"
      class="textarea"
      @input="changeTextarea"
      :value="modelValue"
    ></textarea>
    <p class="error" v-for="error in errors" :key="error.$uid">
      {{ error.$message }}
    </p>
  </div>
</template>
<script>
export default {
  name: "my-textarea",
  inheritAttrs: false,
  props: {
    label: {
      type: String,
      default: "",
    },
    modelValue: {
      type: String,
      default: "",
    },
    errors: {
      type: [Array],
      default: [],
    },
  },
  methods: {
    changeTextarea(e) {
      this.$emit("update:modelValue", e.target.value, e.target.name);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";
.textarea-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
}

.textarea {
  box-shadow: 0 0 5px 3px $mainBoxShadow;
  background-color: white;
  outline: none;
  padding: 5px;
  font-size: 18px;
  width: 100%;
  resize: none;
  height: 200px;

  &:focus {
    outline: 1px solid rgb(0, 183, 255);
  }
}
</style>
