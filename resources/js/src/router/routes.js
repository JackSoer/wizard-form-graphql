import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";
import AllMembers from "@/pages/AllMembers.vue";
import Share from "@/pages/Share.vue";

const routes = [
  {
    path: "/",
    component: Main,
  },
  {
    path: "/share",
    component: Share,
  },
  {
    path: "/login",
    component: Login,
  },
  {
    path: "/all-members",
    component: AllMembers,
  },
];

export default routes;
