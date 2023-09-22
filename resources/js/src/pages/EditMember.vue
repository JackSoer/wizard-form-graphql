<template>
  <div class="edit-member">
    <member-form
      :title="'Edit member'"
      :onlyEdit="true"
      v-if="!isLoading && !errors && member !== null"
      :editId="id"
    />
  </div>
</template>
<script>
import MemberForm from "@/components/MemberForm.vue";
import { mapMutations, useStore } from "vuex";
import useAxiosFetch from "@/hooks/useAxiosFetch";
import { useRoute } from "vue-router";
import { watch } from "vue";

export default {
  components: {
    MemberForm,
  },
  setup() {
    const route = useRoute();
    const store = useStore();
    const id = route.params.id;
    const BASE_URL = import.meta.env.VITE_BASE_URL;

    const {
      responseData: member,
      errors,
      isLoading,
    } = useAxiosFetch(`${BASE_URL}/api/v1/members/${id}`);

    watch(member, (loadedMember) => {
      if (loadedMember !== null) {
        store.commit("member/setMember", loadedMember.data);
      }
    });

    return {
      member,
      errors,
      isLoading,
      id,
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
<style lang="scss" scoped></style>
