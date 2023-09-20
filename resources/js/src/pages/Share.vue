<template>
  <div class="share">
    <div class="share__btns">
      <ShareNetwork
        v-for="share in shareData"
        v-bind="share"
        :key="share.network"
        ><div class="share__btn-box">
          <img
            :src="getImgPath(share.src)"
            :alt="share.network"
            class="share__icon"
          /></div
      ></ShareNetwork>
    </div>
    <div class="share__all-members">
      <router-link to="/all-members"
        >All members ({{
          members?.data.length ? members?.data.length : "0"
        }})</router-link
      >
    </div>
  </div>
</template>
<script>
import shareData from "@/data/shareData";
import useAxiosFetch from "@/hooks/useAxiosFetch";

export default {
  setup() {
    const BASE_URL = import.meta.env.VITE_BASE_URL;

    const { responseData } = useAxiosFetch(`${BASE_URL}/api/v1/members`);

    return {
      shareData,
      members: responseData,
    };
  },
  methods: {
    getImgPath(img) {
      return img ? new URL("../assets/images/" + img, import.meta.url) : "";
    },
  },
};
</script>
<style lang="scss" scoped>
.share {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
  justify-content: center;

  &__all-members {
    font-size: 18px;
    font-weight: 500;
  }

  &__btns {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  &__btn-box {
    width: 70px;
    height: 70px;
    border-radius: 50%;
  }

  &__icon {
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }
}
</style>
