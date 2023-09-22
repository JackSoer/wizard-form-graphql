<template>
  <table class="member-table">
    <thead
      class="member-table__head"
      :class="{
        'member-table__head--admin': $route.path === '/admin-table',
      }"
    >
      <th class="member-table__th">Photo</th>
      <th class="member-table__th">Full name</th>
      <th class="member-table__th">Report subject</th>
      <th class="member-table__th">Email</th>
      <th class="member-table__th" v-if="$route.path === '/admin-table'">
        Options
      </th>
    </thead>
    <tbody class="member-table__body">
      <member-row
        v-for="member in members.slice().reverse()"
        :member="member"
        :key="member.id"
        @remove="remove"
        @editVisibility="editVisibility"
      ></member-row>
    </tbody>
  </table>
</template>
<script>
import MemberRow from "@/components/MemberRow.vue";

export default {
  components: {
    MemberRow,
  },
  props: {
    members: {
      type: Array,
      default: [],
    },
  },
  methods: {
    remove(id) {
      this.$emit("remove", id);
    },
    editVisibility(member) {
      this.$emit("editVisibility", member);
    },
  },
};
</script>
<style lang="scss" scoped>
.member-table {
  max-width: 900px;

  &__head {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;

    &--admin {
      grid-template-columns: 1fr 1fr 1fr 1fr 90px;
    }
  }

  &__th {
    font-size: 18px;
    padding: 10px;
  }
}
</style>
