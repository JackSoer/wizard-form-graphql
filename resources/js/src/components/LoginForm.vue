<template>
  <form class="login" @submit.prevent="submit">
    <h1 class="login__title">Login</h1>
    <MyInput
      label="Login"
      id="login"
      name="login"
      v-model.trim="login"
      :autofocused="true"
      required="true"
    />
    <MyInput
      label="Password"
      id="password"
      name="password"
      type="password"
      v-model="password"
      required="true"
    />
    <MyButton class="login__submit" :isLoading="isLoading">Send</MyButton>
    <div class="errors" v-if="errors && !isLoading">
      <p class="error" v-for="error in errors">{{ error }}</p>
    </div>
  </form>
</template>
<script>
import { ref } from "vue";
import { mapState, mapActions } from "vuex";

export default {
  setup() {
    const login = ref("");
    const password = ref("");

    return {
      login,
      password,
    };
  },
  computed: {
    ...mapState({
      isLoading: (state) => state.auth.isLoading,
      errors: (state) => state.auth.errors,
    }),
  },
  methods: {
    ...mapActions({
      loginAction: "auth/login",
    }),
    async submit() {
      if (!this.login || !this.password) {
        return;
      }

      const creds = { login: this.login, password: this.password };

      await this.loginAction(creds);

      if (!this.errors) {
        this.$router.push("/admin-table");
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.login {
  max-width: 300px;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;

  &__submit {
    width: 100% !important;
    max-width: 100% !important;
    margin-top: 15px;
  }

  &__title {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 12px;
  }
}

.error {
  font-size: 14px;
  font-weight: 500;
}
</style>
