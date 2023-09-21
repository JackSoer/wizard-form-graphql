import axios from "axios";

const BASE_URL = import.meta.env.VITE_BASE_URL;

const authModule = {
  state: () => ({
    user: null,
    isLoading: false,
    errors: null,
  }),
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setIsLoading(state, isLoading) {
      state.isLoading = isLoading;
    },
    setErrors(state, errors) {
      state.errors = errors;
    },
  },
  actions: {
    async login({ commit }, credentials) {
      try {
        commit("setIsLoading", true);

        const response = await axios.post(
          `${BASE_URL}/api/v1/login`,
          credentials
        );
        const user = response.data;

        commit("setUser", user);

        commit("setErrors", null);
      } catch (err) {
        console.log(err);

        if (err?.response?.data?.errors) {
          commit("setErrors", err?.response?.data?.errors);
        } else {
          commit("setErrors", [err?.response?.data?.message]);
        }
      } finally {
        commit("setIsLoading", false);
      }
    },
  },
  namespaced: true,
};

export default authModule;
