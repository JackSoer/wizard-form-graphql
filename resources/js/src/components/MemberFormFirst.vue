<template>
  <div class="memeber-form-first">
    <MyInput
      v-for="input in inputDataFirst"
      :label="input.label"
      :key="input.id"
      v-bind="input"
      :model-value="member[input.name]"
      @update:model-value="changeMember"
      :errors="validator[input.name].$errors"
      @blur="validator[input.name].$touch"
      :autofocused="input.name === 'firstName' ? true : false"
    />
    <BirthdateInput
      required="true"
      @changeDate="changeMember"
      :model-value="member.birthdate"
      :errors="validator.birthdate.$errors"
      @blur="validator.birthdate.$touch"
    />
    <MyInput
      label="Report Subject"
      name="reportSubject"
      id="reportSubject"
      required="true"
      maxlenght="100"
      minlenght="2"
      :model-value="member.reportSubject"
      @update:model-value="changeMember"
      :errors="validator.reportSubject.$errors"
      @blur="validator.reportSubject.$touch"
    />
    <MySelect
      :items="countries"
      name="country"
      id="country"
      required="true"
      label="Country"
      :model-value="member.country"
      @update:model-value="changeMember"
      :errors="validator.country.$errors"
      @blur="validator.country.$touch"
    />
    <PhoneInput
      @changePhone="changeMember"
      :model-value="member.phone"
      @isValid="handlePhoneError"
      :nextClickCount="nextClickCount"
    />
    <MyInput
      label="Email"
      type="email"
      name="email"
      id="email"
      required="true"
      :model-value="member.email"
      @update:model-value="changeMember"
      :errors="validator.email.$errors"
      :requestErrors="requestErrors?.email"
      @blur="validator.email.$touch"
      maxlenght="100"
      minlenght="3"
      :nextClickCount="nextClickCount"
      :isLoading="isLoading"
    />
  </div>
</template>
<script>
import PhoneInput from "@/components/PhoneInput.vue";
import BirthdateInput from "@/components/BirthdateInput.vue";
import inputData from "@/data/inputData";
import { mapState, mapMutations } from "vuex";
import gql from "graphql-tag";
import { useQuery } from "@vue/apollo-composable";
import { ref, watch } from "vue";

export default {
  components: { BirthdateInput, PhoneInput },
  setup() {
    const inputDataFirst = inputData.firstPart.slice(0, 2);
    const inputDataSecond = inputData.firstPart.slice(3);

    const countries = ref([]);

    const { result } = useQuery(gql`
      query {
        countries {
          code
          name
        }
      }
    `);

    watch(result, (newResult) => {
      countries.value = newResult.countries.map((country) => ({
        ...country,
        value: country.name,
      }));
    });

    return {
      inputDataFirst,
      inputDataSecond,
      countries,
    };
  },
  methods: {
    ...mapMutations({
      changeMemberField: "member/changeMemberField",
    }),
    changeMember(value, field) {
      this.changeMemberField({ field, value });
    },
    handlePhoneError(isValid) {
      this.$emit("validatePhone", isValid);
    },
  },
  computed: {
    ...mapState({
      member: (state) => state.member.member,
    }),
  },
  props: {
    validator: {
      type: Object,
      required: true,
    },
    nextClickCount: {
      type: Number,
      required: true,
    },
    requestErrors: {
      type: [Object, null],
      default: null,
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
<style lang="scss" scoped>
.memeber-form-first {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 10px;
}
</style>
