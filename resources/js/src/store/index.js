import { createStore } from "vuex";
import memberModule from "@/store/memberModule";

export default createStore({
  modules: {
    member: memberModule,
  },
});
