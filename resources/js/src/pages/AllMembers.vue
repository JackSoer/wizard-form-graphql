<template>
  <div class="all-members">
    <div class="container">
      <members-table
        v-if="!errors && !isLoading"
        :members="members?.members"
      ></members-table>
    </div>
  </div>
</template>
<script>
import MembersTable from "@/components/MembersTable.vue";
import Loading from "@/components/Loading.vue";
import gql from "graphql-tag";
import { useQuery } from "@vue/apollo-composable";

export default {
  components: {
    MembersTable,
    Loading,
  },
  setup() {
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

    const { result: responseData } = useQuery(membersQuery);

    return {
      members: responseData,
    };
  },
};
</script>
<style lang="scss" scoped>
.all-members {
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
