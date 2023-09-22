import { createRouter, createWebHistory } from "vue-router";
import routes from "@/router/routes";
import store from "@/store/index";

const router = createRouter({
  routes,
  history: createWebHistory(),
});

router.beforeEach((to, from, next) => {
  const user = store.state.auth.user;

  if (to.meta.adminRequired && !user?.user?.isAdmin) {
    next("/login");
  } else {
    next();
  }
});

export default router;
