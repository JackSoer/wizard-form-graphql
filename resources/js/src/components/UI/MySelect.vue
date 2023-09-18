<template>
  <div class="my-select-box">
    <label :for="$attrs.id" class="my-select-label">{{ label }}</label>
    <select
      class="my-select"
      v-bind="$attrs"
      @input="change"
      :value="modelValue"
    >
      <option value="" class="my-select__option" disabled></option>
      <option
        v-for="item in items"
        :value="item.value"
        class="my-select__option"
        :key="item.name"
      >
        {{ item.name }}
      </option>
    </select>
    <p class="error" v-for="error in errors" :key="error.$uid">
      {{ error.$message }}
    </p>
  </div>
</template>
<script>
export default {
  inheritAttrs: false,
  name: "my-select",
  props: {
    items: {
      type: Array,
      required: true,
    },
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
    change(e) {
      this.$emit("update:modelValue", e.target.value, e.target.name);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";

.my-select-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
}

.my-select {
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
</style>
