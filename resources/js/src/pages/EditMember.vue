<template>
  <div class="edit-member">
    <member-form
      :title="'Edit member'"
      :onlyEdit="true"
      v-if="!isLoading && !errors && member !== null"
      :editId="id"
    />
    <loading v-if="!errors && isLoading"></loading>
    <div class="errors" v-if="!isLoading && errors">
      <p class="error" v-for="error in errors">{{ error }}</p>
    </div>
  </div>
</template>
<script>
import MemberForm from "@/components/MemberForm.vue";
import { mapMutations, useStore } from "vuex";
import useAxiosFetch from "@/hooks/useAxiosFetch";
import { useRoute } from "vue-router";
import { watch } from "vue";
import Loading from "@/components/Loading.vue";

export default {
  components: {
    MemberForm,
    Loading,
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
