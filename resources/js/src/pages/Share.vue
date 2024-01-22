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
          members?.members.length ? members?.members.length : "0"
        }})</router-link
      >
    </div>
  </div>
</template>
<script>
import shareData from "@/data/shareData";
import gql from "graphql-tag";
import { useQuery } from "@vue/apollo-composable";
import { onMounted } from "vue";

export default {
  setup() {
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

    onMounted(() => {
      refetch();
    });

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
  min-height: 100vh;
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
