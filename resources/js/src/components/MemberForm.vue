<template>
  <div class="member-form">
    <h1 class="member-form__title">
      To participate in the conference, please fill out the form
    </h1>
    <p class="member-form__steps-count">{{ currentStep }} / 2</p>
    <form
      class="member-form__item"
      enctype="multipart/form-data"
      @submit.prevent="submitMember"
    >
      <member-form-first
        v-if="currentStep === 1"
        :validator="v$"
        @validatePhone="validatePhone"
        :nextClickCount="nextClickCount"
        :requestErrors="requestErrors"
        :isLoading="isLoading"
      />
      <member-form-second
        v-if="currentStep === 2"
        :validator="v$"
        @photoErrors="validatePhoto"
      />
      <div
        class="member-form__btns"
        :class="{ 'member-form__btns--start': isFirstStep() }"
      >
        <my-button @click="back" v-if="!isFirstStep()" type="button">
          Back
        </my-button>
        <my-button
          class="member-form__next-btn"
          type="submit"
          :disabled="isLoading === true"
        >
          {{ isLastStep() ? "Finish" : "Next" }}
        </my-button>
      </div>
    </form>
  </div>
</template>
<script>
import MemberFormSecond from "@/components/MemberFormSecond.vue";
import MemberFormFirst from "@/components/MemberFormFirst.vue";
import useMultiform from "@/hooks/useMultiform";
import { mapState, mapMutations, useStore } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required, maxLength, minLength, email } from "@vuelidate/validators";
import { ref, computed } from "vue";

export default {
  components: { MemberFormFirst, MemberFormSecond },
  data() {
    return {
      requestErrors: null,
      isLoading: false,
    };
  },
  computed: {
    ...mapState({
      member: (state) => state.member.member,
    }),
  },
  setup() {
    const { currentStep, prev, next, isLastStep, isFirstStep } =
      useMultiform(2);

    let rules = {
      firstName: {
        required,
        minLength: minLength(2),
        maxLenght: maxLength(30),
      },
      lastName: {
        required,
        minLength: minLength(2),
        maxLenght: maxLength(30),
      },
      birthdate: { required },
      reportSubject: {
        required,
        minLength: minLength(2),
        maxLenght: maxLength(100),
      },
      country: { required },
      phone: { required },
      email: { required, email },
      company: { maxLenght: maxLength(40) },
      position: { maxLenght: maxLength(40) },
      aboutMe: { maxLenght: maxLength(2000) },
      photo: {},
    };

    const store = useStore();

    const member = computed(() => store.state.member.member);

    let v$ = useVuelidate(rules, member.value);

    const clearLocalStorage = () => {
      localStorage.removeItem("member");
      localStorage.removeItem("currentStep");
    };

    const back = () => {
      store.commit("member/changeMemberField", { field: "photo", value: null });
      clearLocalStorage();
      prev();
    };

    const phoneIsValid = ref(false);
    const photoErrors = ref([]);
    const nextClickCount = ref(0);

    return {
      currentStep,
      next,
      isLastStep,
      isFirstStep,
      v$,
      phoneIsValid,
      photoErrors,
      rules,
      back,
      nextClickCount,
      member,
    };
  },
  methods: {
    ...mapMutations({
      setMemberFromLocalStorage: "member/setMemberFromLocalStorage",
      clearMember: "member/clearMember",
    }),
    async submitMember() {
      let member = this.member;

      if (this.isFirstStep()) {
        member = {
          ...member,
          company: "",
          position: "",
          aboutMe: "",
          photo: null,
        };
        this.photoErrors = [];
      }

      const validation = useVuelidate(this.rules, member);

      const isValid = await validation.value.$validate();

      this.nextClickCount += 1;

      if (isValid && this.phoneIsValid && this.photoErrors.length <= 0) {
        const userId = localStorage.getItem("userId") || null;

        const formData = new FormData();

        for (const key in member) {
          if (key === "photo" && !member[key]) {
            continue;
          }

          formData.append(key, member[key]);
        }

        const BASE_URL = import.meta.env.VITE_BASE_URL;

        if (userId) {
          formData.append("_method", "PUT");

          try {
            this.isLoading = true;

            await axios.post(`${BASE_URL}/api/v1/members/${userId}`, formData);

            this.requestErrors = null;
          } catch (err) {
            console.log(err);

            if (err?.response?.data?.errors) {
              this.requestErrors = err?.response?.data?.errors;
            } else {
              this.requestErrors = err?.response?.data?.message;
            }
          } finally {
            this.isLoading = false;
          }
        } else {
          try {
            this.isLoading = true;

            const response = await axios.post(
              `${BASE_URL}/api/v1/members`,
              formData
            );

            localStorage.setItem("userId", response.data.data.id);

            this.requestErrors = null;
          } catch (err) {
            console.log(err);

            if (err?.response?.data?.errors) {
              this.requestErrors = err?.response?.data?.errors;
            } else {
              this.requestErrors = err?.response?.data?.message;
            }
          } finally {
            this.isLoading = false;
          }
        }

        if (this.requestErrors) {
          return;
        }

        if (!this.isLastStep()) {
          localStorage.setItem("member", JSON.stringify(member));

          this.next();

          localStorage.setItem("currentStep", this.currentStep);
        } else {
          localStorage.removeItem("currentStep");
          localStorage.removeItem("member");
          localStorage.removeItem("userId");

          this.clearMember();

          this.$router.push("/share");
        }
      }
    },
    validatePhone(isValid) {
      this.phoneIsValid = isValid;
    },
    validatePhoto(errors) {
      this.photoErrors = errors;
    },
  },
  mounted() {
    this.setMemberFromLocalStorage();
    this.v$ = useVuelidate(this.rules, this.member);

    if (this.currentStep === 1) {
      localStorage.removeItem("userId");
    }
  },
};
</script>
<style lang="scss" scoped>
.member-form {
  width: 100%;
  padding: 50px 0;

  &__item {
    max-width: 350px;
    margin: 0 auto;
  }

  &__title {
    font-size: 26px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 18px;
  }

  &__btns {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 18px;

    &--start {
      justify-content: flex-end;
    }
  }

  &__steps-count {
    text-align: center;
    font-weight: 500;
    font-size: 16px;
    margin-bottom: 10px;
  }
}
</style>
