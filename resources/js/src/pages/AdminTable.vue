<template>
  <div class="admin-table">
    <div class="container">
      <members-table
        v-if="!errors && !isLoading"
        :members="members?.data"
        @remove="remove"
        @editVisibility="editVisibility"
      ></members-table>
      <loading v-if="!errors && isLoading"></loading>
      <div class="errors" v-if="!isLoading && errors">
        <p class="error" v-for="error in errors">{{ error }}</p>
      </div>
    </div>
  </div>
</template>
<script>
import useAxiosFetch from "@/hooks/useAxiosFetch";
import useAxiosRequest from "@/hooks/useAxiosRequest";
import MembersTable from "@/components/MembersTable.vue";
import Loading from "@/components/Loading.vue";
import { mapState, useStore } from "vuex";
import { computed } from "vue";

export default {
  components: {
    MembersTable,
    Loading,
  },
  setup() {
    const store = useStore();

    const user = computed(() => store.state.auth.user);

    const BASE_URL = import.meta.env.VITE_BASE_URL;

    const { responseData, isLoading, errors } = useAxiosFetch(
      `${BASE_URL}/api/v1/members`
    );

    const { axiosRequest, errors: deleteErrors } = useAxiosRequest("delete", {
      Authorization: `Bearer ${user.value?.token}`,
    });

    const remove = async (id) => {
      await axiosRequest(`${BASE_URL}/api/v1/members/${id}`);
      if (deleteErrors.value) {
        return;
      }

      responseData.value.data = responseData.value.data.filter(
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

      responseData.value.data = responseData.value.data.map((memberData) =>
        memberData.id === member.id
          ? { ...memberData, isVisible: !memberData.isVisible }
          : memberData
      );
    };

    return {
      members: responseData,
      isLoading,
      errors,
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
