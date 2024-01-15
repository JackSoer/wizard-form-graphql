<template>
  <div class="admin-table">
    <div class="container">
      <members-table
        v-if="!errors && !isLoading"
        :members="members?.members"
        @remove="remove"
        @editVisibility="editVisibility"
      ></members-table>
    </div>
  </div>
</template>
<script>
import useAxiosRequest from "@/hooks/useAxiosRequest";
import MembersTable from "@/components/MembersTable.vue";
import Loading from "@/components/Loading.vue";
import { mapState, useStore } from "vuex";
import { computed } from "vue";
import gql from "graphql-tag";
import { useQuery } from "@vue/apollo-composable";

export default {
  components: {
    MembersTable,
    Loading,
  },
  setup() {
    const store = useStore();

    const user = computed(() => store.state.auth.user);

    const BASE_URL = import.meta.env.VITE_BASE_URL;

    const membersQuery = gql`
      query {
        members {
          id
          firstName: first_name
          lastName: last_name
          birthdate
          reportSubject: report_subject
          country
          phone
          email
          company
          position
          aboutMe: about_me
          photo
          isVisible: is_visible
          createdAt: created_at
          updatedAt: updated_at
        }
      }
    `;

    const { result: responseData, refetch } = useQuery(membersQuery);

    const { axiosRequest, errors: deleteErrors } = useAxiosRequest("delete", {
      Authorization: `Bearer ${user.value?.token}`,
    });

    const remove = async (id) => {
      await axiosRequest(`${BASE_URL}/api/v1/members/${id}`);
      if (deleteErrors.value) {
        return;
      }

      await refetch();

      responseData.value.members = responseData.value.members.filter(
        (member) => member.id !== id
      );
    };

    const { axiosRequest: postRequest, errors: editErrors } =
      useAxiosRequest("post");

    const editVisibility = async (member) => {
      await postRequest(`${BASE_URL}/api/v1/members/${member.id}`, {
        isVisible: !member.isVisible,
        _method: "PATCH",
      });

      if (editErrors.value) {
        return;
      }

      await refetch();

      responseData.value.members = responseData.value.members.map(
        (memberData) =>
          memberData.id === member.id
            ? { ...memberData, isVisible: !memberData.isVisible }
            : memberData
      );
    };

    return {
      members: responseData,
      remove,
      editVisibility,
    };
  },
  computed: {
    ...mapState({
      user: (state) => state.auth.user,
    }),
  },
};
</script>
<style lang="scss" scoped>
.admin-table {
  width: 100%;
  min-height: 100vh;
  display: flex;
  padding: 20px 0;
}
.errors {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
}

.error {
  font-size: 16px;
  font-weight: 500;
}
</style>
