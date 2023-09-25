<template>
  <div class="member-form">
    <h1 class="member-form__title">
      {{ title }}
    </h1>
    <p class="member-form__steps-count" v-if="!onlyEdit">
      {{ currentStep }} / 2
    </p>
    <form
      class="member-form__item"
      enctype="multipart/form-data"
      @submit.prevent="submitMember"
    >
      <MemberFormFirst
        v-if="currentStep === 1 || onlyEdit"
        :validator="v$"
        @validatePhone="validatePhone"
        :nextClickCount="nextClickCount"
        :requestErrors="requestErrors"
        :isLoading="isLoading"
      />
      <MemberFormSecond
        v-if="currentStep === 2 || onlyEdit"
        :validator="v$"
        @photoErrors="validatePhoto"
        :onlyEdit="onlyEdit"
      />
      <div
        class="member-form__btns"
        :class="{ 'member-form__btns--start': isFirstStep() }"
      >
        <MyButton
          @click="back"
          v-if="!isFirstStep() && !onlyEdit"
          type="button"
        >
          Back
        </MyButton>
        <MyButton
          class="member-form__next-btn"
          type="submit"
          :isLoading="isLoading === true"
        >
          {{ onlyEdit ? "Edit" : isLastStep() ? "Finish" : "Next" }}
        </MyButton>
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
  props: {
    onlyEdit: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "To participate in the conference, please fill out the form",
    },
    editId: {
      type: String,
    },
  },
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

      if (this.isFirstStep() && !this.onlyEdit) {
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
          if (key === "photo" && !member[key] && !this.onlyEdit) {
            continue;
          }

          if (!member[key]) {
            formData.append(key, "");
          } else {
            formData.append(key, member[key]);
          }
        }
        const BASE_URL = import.meta.env.VITE_BASE_URL;

        if (userId || this.onlyEdit) {
          formData.append("_method", "PUT");
          const id = this.onlyEdit ? this.editId : userId;

          try {
            this.isLoading = true;

            await axios.post(`${BASE_URL}/api/v1/members/${id}`, formData);

            this.requestErrors = null;
          } catch (err) {
            console.log(err);

            if (err?.response?.data?.errors) {
              this.requestErrors = err?.response?.data?.errors;
            } else {
              this.requestErrors = [err?.response?.data?.message];
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
              this.requestErrors = [err?.response?.data?.message];
            }
          } finally {
            this.isLoading = false;
          }
        }

        if (this.requestErrors) {
          return;
        }

        if (!this.isLastStep() && !this.onlyEdit) {
          localStorage.setItem("member", JSON.stringify(member));

          this.next();

          localStorage.setItem("currentStep", this.currentStep);
        } else if (!this.onlyEdit) {
          localStorage.removeItem("currentStep");
          localStorage.removeItem("member");
          localStorage.removeItem("userId");

          this.clearMember();

          this.$router.push("/share");
        } else {
          this.$router.push("/admin-table");
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
    if (!this.onlyEdit) {
      this.setMemberFromLocalStorage();
      this.v$ = useVuelidate(this.rules, this.member);

      if (this.currentStep === 1) {
        localStorage.removeItem("userId");
      }
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
    display: flex;
    flex-direction: column;
    gap: 10px;
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
    margin-top: 10px;

    &--start {
      justify-content: flex-end;
    }
  }

  &__steps-count {
    text-align: center;
    font-weight: 500;
    font-size: 18px;
    margin-bottom: 10px;
  }
}
</style>
