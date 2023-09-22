import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";
import AllMembers from "@/pages/AllMembers.vue";
import Share from "@/pages/Share.vue";
import AdminTable from "@/pages/AdminTable.vue";
import EditMember from "@/pages/EditMember.vue";

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
  {
    path: "/admin-table",
    component: AdminTable,
    meta: {
      adminRequired: true,
    },
  },
  {
    path: "/edit-member/:id",
    component: EditMember,
    meta: {
      adminRequired: true,
    },
  },
];

export default routes;
