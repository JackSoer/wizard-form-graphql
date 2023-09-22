<template>
  <div class="file-box">
    <label :for="$attrs.id" class="file-label">{{ label }}</label>
    <div class="file-box__info">
      <div class="hints">
        <p class="hint">Max size: {{ maxMb }} MB</p>
        <p class="hint">Allowed types: {{ allowedTypes.join(", ") }}</p>
      </div>
      <div
        class="edited-avatar"
        v-if="typeof modelValue === 'string' && !changing"
      >
        <img
          :src="BASE_URL + modelValue"
          alt="Edited avatar"
          class="edited-avatar__item"
        />
      </div>
    </div>
    <input
      type="file"
      class="file-input"
      v-bind="$attrs"
      @input="changeFile"
      @mouseup="changeFile"
    />
    <p
      class="error"
      v-for="validationError in validationErrors"
      :key="validationError"
    >
      {{ validationError }}
    </p>
  </div>
</template>
<script>
export default {
  inheritAttrs: false,
  props: {
    label: {
      type: String,
      default: "",
    },
    modelValue: {
      type: [File, null, String],
      default: null,
    },
    maxMb: {
      type: Number,
      default: Infinity,
    },
    allowedTypes: {
      type: Array,
      default: [""],
    },
  },
  computed: {
    validationErrors() {
      let errors = [];

      if (this.modelValue?.name) {
        let isValidFileType = false;

        this.allowedTypes.forEach((allowedType) => {
          if (this.modelValue.type.startsWith(allowedType)) {
            isValidFileType = true;
          }
        });

        if (!isValidFileType) {
          errors.push("Incorrect file type");
        }

        const maxSize = this.maxMb * 1024 * 1024;

        if (this.modelValue.size > maxSize) {
          errors.push("Incorrect file size");
        }
      }

      return errors;
    },
  },
  methods: {
    changeFile(e) {
      this.changing = true;
      this.$emit("update:modelValue", e.target.files[0], e.target.name);
    },
  },
  watch: {
    validationErrors() {
      this.$emit("errors", this.validationErrors);
    },
  },
  setup() {
    const BASE_URL = import.meta.env.VITE_BASE_URL;

    return {
      BASE_URL,
    };
  },
  data() {
    return {
      changing: false,
    };
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";

.file-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;

  &__info {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
}

.file-input {
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

.hints {
  display: flex;
  flex-direction: column;
  gap: 3px;
  margin-bottom: 4px;
}

.hint {
  font-size: 11px;
  color: gray;
}

.edited-avatar {
  width: 30px;
  height: 30px;

  &__item {
    height: 100%;
    width: 100%;
  }
}
</style>
