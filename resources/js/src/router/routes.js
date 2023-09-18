import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";
import Dashboard from "@/pages/Dashboard.vue";

const routes = [
  {
    path: "/",
    component: Main,
  },
  {
    path: "/login",
    component: Login,
  },
  {
    path: "/dashboard",
    component: Dashboard,
  },
];

export default routes;
