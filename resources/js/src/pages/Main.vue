<template>
  <div class="home">
    <google-map />
    <member-form />
  </div>
</template>

<script>
import MemberForm from "@/components/MemberForm.vue";
import GoogleMap from "@/components/GoogleMap.vue";

export default {
  components: { GoogleMap, MemberForm },
};
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
}
</style>
