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
        <MyButton class="member-form__next-btn" type="submit">
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
import { ref, computed, onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
import gql from "graphql-tag";
import { useMutation } from "@vue/apollo-composable";

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
  computed: {
    ...mapState({
      member: (state) => state.member.member,
    }),
  },
  setup({ onlyEdit, editId }) {
    const { currentStep, prev, next, isLastStep, isFirstStep } =
      useMultiform(2);

    const router = useRouter();
    const requestErrors = reactive({ email: "" });

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

    onMounted(() => {
      if (!onlyEdit) {
        store.commit("member/setMemberFromLocalStorage");
        v$ = useVuelidate(rules, member);

        if (currentStep === 1) {
          localStorage.removeItem("userId");
        }
      }
    });

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

    const CREATE_MEMBER_MUTATION = gql`
      mutation CreateMember($input: MemberInput!) {
        createMember(input: $input) {
          id
        }
      }
    `;

    const { mutate: createMemberMutation } = useMutation(
      CREATE_MEMBER_MUTATION
    );

    const EDIT_MEMBER_MUTATION = gql`
      mutation EditMember($id: ID!, $input: MemberInput, $photo: Upload) {
        editMember(id: $id, input: $input, photo: $photo) {
          id
        }
      }
    `;

    const { mutate: editMemberMutation } = useMutation(EDIT_MEMBER_MUTATION, {
      context: {
        hasUpload: true,
      },
    });

    const submitMember = async () => {
      let member = store.state.member.member;

      if (isFirstStep() && !onlyEdit) {
        member = {
          ...member,
          company: "",
          position: "",
          aboutMe: "",
          photo: null,
        };
        photoErrors.value = [];
      }

      const validation = useVuelidate(rules, member);

      const isValid = await validation.value.$validate();

      nextClickCount.value += 1;

      requestErrors.email = "";

      if (
        (isValid && phoneIsValid.value && photoErrors.value.length <= 0) ||
        currentStep.value === 2
      ) {
        const BASE_URL = import.meta.env.VITE_BASE_URL;

        const userId = localStorage.getItem("userId") || null;

        const id = onlyEdit ? editId : userId;

        const { photo, ...memberWithoutPhoto } = member;

        if (userId || onlyEdit) {
          console.log(member.photo);
          try {
            const { data } = await editMemberMutation({
              id,
              input: {
                ...memberWithoutPhoto,
                isVisible: Boolean(member.isVisible),
              },
              photo: member.photo,
            });
            console.log(data);
          } catch (err) {
            console.log(err);
          }
        } else {
          try {
            const { data } = await createMemberMutation({
              input: {
                ...memberWithoutPhoto,
                isVisible: Boolean(member.isVisible),
              },
            });
            console.log(data);

            localStorage.setItem("userId", data.createMember.id);
          } catch (err) {
            console.log(err);

            requestErrors.email = ["Email already taken"];
          }
        }

        if (!requestErrors.email) {
          if (!isLastStep() && !onlyEdit) {
            localStorage.setItem("member", JSON.stringify(member));

            next();

            localStorage.setItem("currentStep", currentStep.value);
          } else if (!onlyEdit) {
            localStorage.removeItem("currentStep");
            localStorage.removeItem("member");
            localStorage.removeItem("userId");

            store.commit("member/clearMember");

            router.push("/share");
          } else {
            router.push("/admin-table");
          }
        }
      }
    };

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
      submitMember,
      requestErrors,
    };
  },
  methods: {
    ...mapMutations({
      setMemberFromLocalStorage: "member/setMemberFromLocalStorage",
      clearMember: "member/clearMember",
    }),
    validatePhone(isValid) {
      this.phoneIsValid = isValid;
    },
    validatePhoto(errors) {
      this.photoErrors = errors;
    },
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
