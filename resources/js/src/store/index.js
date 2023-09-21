import { createStore } from "vuex";
import memberModule from "@/store/memberModule";
import authModule from "@/store/authModule";

export default createStore({
  modules: {
    member: memberModule,
    auth: authModule,
  },
});
