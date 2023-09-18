const memberModule = {
  state: () => ({
    member: {
      firstName: "",
      lastName: "",
      birthdate: "",
      reportSubject: "",
      country: "",
      phone: "",
      email: "",
      company: "",
      position: "",
      aboutMe: "",
      photo: null,
    },
  }),
  mutations: {
    changeMemberField(state, payload) {
      state.member[payload.field] = payload.value;
    },
    setMember(state, member) {
      state.member = member;
    },
    setMemberFromLocalStorage(state) {
      const memberFromLocalStorage = JSON.parse(localStorage.getItem("member"));

      if (memberFromLocalStorage) {
        state.member = memberFromLocalStorage;
      }
    },
  },
  namespaced: true,
};

export default memberModule;
