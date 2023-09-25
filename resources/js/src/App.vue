<template>
  <main class="app">
    <MainHeader v-if="isAdmin" />
    <router-view></router-view>
  </main>
</template>
<script>
import MainHeader from "@/components/MainHeader.vue";
import { useStore } from "vuex";
import { computed } from "vue";
export default {
  components: {
    MainHeader,
  },
  setup() {
    const store = useStore();

    const isAdmin = computed(() => store.state.auth.user?.user?.isAdmin);

    return {
      isAdmin,
    };
  },
};
</script>
<style lang="scss"></style>
