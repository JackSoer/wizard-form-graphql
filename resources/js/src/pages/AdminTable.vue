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
import { useMutation, useQuery } from "@vue/apollo-composable";

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

    const DELETE_MEMBER_MUTATION = gql`
      mutation DeleteMember($id: ID!) {
        deleteMember(id: $id)
      }
    `;

    const { mutate: deleteMemberMutation } = useMutation(
      DELETE_MEMBER_MUTATION
    );

    const remove = async (id) => {
      await deleteMemberMutation({ id });

      await refetch();

      responseData.value.members = responseData.value.members.filter(
        (member) => member.id !== id
      );
    };

    const EDIT_MEMBER_MUTATION = gql`
      mutation EditMember($id: ID!, $input: MemberInput, $photo: Upload) {
        editMember(id: $id, input: $input, photo: $photo) {
          id
        }
      }
    `;

    const { mutate: editMemberMutation } = useMutation(EDIT_MEMBER_MUTATION, {
      context: {
        hasUpload: true,
      },
    });

    const editVisibility = async (member) => {
      const {
        photo,
        createdAt,
        updatedAt,
        id,
        __typename,
        ...memberWithoutPhoto
      } = member;

      const { data } = await editMemberMutation({
        id,
        input: {
          ...memberWithoutPhoto,
          isVisible: Boolean(!member.isVisible),
        },
      });
      console.log(data);

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
