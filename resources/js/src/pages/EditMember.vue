<template>
  <div class="edit-member">
    <member-form
      :title="'Edit member'"
      :onlyEdit="true"
      v-if="member !== null"
      :editId="id"
    />
  </div>
</template>
<script>
import MemberForm from "@/components/MemberForm.vue";
import { mapMutations, useStore } from "vuex";
import { useRoute } from "vue-router";
import { watch } from "vue";
import Loading from "@/components/Loading.vue";
import gql from "graphql-tag";
import { useQuery } from "@vue/apollo-composable";

export default {
  components: {
    MemberForm,
    Loading,
  },
  setup() {
    const route = useRoute();
    const store = useStore();
    const id = route.params.id;

    const membersQuery = gql`
      query getMember($id: ID!) {
        member(id: $id) {
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

    const { result: member, error } = useQuery(membersQuery, {
      id,
    });

    watch(member, (loadedMember) => {
      if (loadedMember !== null) {
        store.commit("member/setMember", loadedMember.member);
      }
    });

    return {
      member,
      id,
      error,
    };
  },
  methods: {
    ...mapMutations({
      setMember: "member/setMember",
      clearMember: "member/clearMember",
    }),
  },
  beforeRouteLeave() {
    this.clearMember();
  },
};
</script>
<style lang="scss" scoped>
.edit-member {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
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
