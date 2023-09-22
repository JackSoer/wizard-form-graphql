<template>
  <div class="all-members">
    <div class="container">
      <members-table
        v-if="!errors && !isLoading"
        :members="members?.data"
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
import MembersTable from "@/components/MembersTable.vue";
import Loading from "@/components/Loading.vue";

export default {
  components: {
    MembersTable,
    Loading,
  },
  setup() {
    const BASE_URL = import.meta.env.VITE_BASE_URL;

    const { responseData, isLoading, errors } = useAxiosFetch(
      `${BASE_URL}/api/v1/members`
    );

    return {
      members: responseData,
      isLoading,
      errors,
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
