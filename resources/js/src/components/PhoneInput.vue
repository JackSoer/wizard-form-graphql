<template>
  <div class="phone-box">
    <label for="phone" class="phone-label">Phone</label>
    <vue-tel-input
      v-model="phone"
      @on-input="changePhone"
      v-bind="phoneOptions"
      class="phone-input"
      :model-value="modelValue"
      @validate="validate"
      @blur="onBlur"
    ></vue-tel-input>
    <p class="error" v-if="!isValid && focused">Incorrect phone</p>
  </div>
</template>
<script>
import { VueTelInput } from "vue-tel-input";
import "vue-tel-input/vue-tel-input.css";
import { ref, toRefs, watch } from "vue";
export default {
  components: { VueTelInput },
  props: {
    modelValue: {
      type: String,
      default: "",
    },
    nextClickCount: {
      type: Number,
      required: true,
    },
  },
  methods: {
    changePhone() {
      this.$emit(
        "changePhone",
        this.phone,
        this.phoneOptions.inputOptions.name
      );
    },
    validate(phoneData) {
      this.isValid = phoneData.valid;
      this.$emit("isValid", this.isValid);
    },
    onBlur() {
      this.focused = true;
    },
  },
  setup(props) {
    const phoneOptions = {
      validCharactersOnly: true,
      mode: "international",
      inputOptions: {
        required: true,
        id: "phone",
        name: "phone",
        showDialCode: true,
        styleClasses: "phone-input",
        maxlength: 25,
      },
      dropdownOptions: {
        showSearchBox: true,
        showFlags: true,
      },
    };
    const phone = ref("");
    const isValid = ref(false);
    const focused = ref(false);

    const { nextClickCount } = toRefs(props);

    watch(nextClickCount, () => {
      focused.value += 1;
    });

    return { phoneOptions, phone, isValid, focused };
  },
};
</script>
<style lang="scss" scoped>
@import "@/../../scss/var";
.phone-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
}

.phone-input {
  box-shadow: 0 0 5px 3px $mainBoxShadow;
  background-color: white;
  outline: none;
  height: 35px;
  width: 100%;
  border-radius: 0;
}
</style>
