<template>
  <my-input
    :label="label"
    v-bind="inputData"
    :max="maxDate"
    :model-value="modelValue"
    @update:model-value="change"
    :errors="errors"
  />
</template>
<script>
import { ref } from "vue";
export default {
  props: {
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
    change(value, name) {
      this.$emit("changeDate", value, name);
    },
  },
  setup() {
    const label = "Birthdate";
    const inputData = {
      name: "birthdate",
      type: "date",
      id: "birthdate",
    };
    const maxDate = new Date().toISOString().split("T")[0];
    const value = ref("");

    return {
      label,
      inputData,
      maxDate,
      value,
    };
  },
};
</script>
<style lang="scss" scoped></style>
