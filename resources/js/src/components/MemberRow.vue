<template>
  <tr class="member-row">
    <td class="member-row__column">
      <div class="member-row__img">
        <img
          :src="
            member.photo ? BASE_URL + member.photo : getImgPath('defAvatar.jpg')
          "
          alt="member"
          class="member-row__img-item"
        />
      </div>
    </td>
    <td class="member-row__column">
      <p class="member-row__text">
        {{ `${member.firstName} ${member.lastName}` }}
      </p>
    </td>
    <td class="member-row__column">
      <p class="member-row__text">{{ member.reportSubject }}</p>
    </td>
    <td class="member-row__column">
      <p class="member-row__text">
        <a :href="`mailto:${member.email}`" class="member-row__link">{{
          member.email
        }}</a>
      </p>
    </td>
  </tr>
</template>
<script>
export default {
  setup() {
    const BASE_URL = import.meta.env.VITE_BASE_URL;

    return {
      BASE_URL,
    };
  },
  props: {
    member: {
      type: Object,
      required: true,
    },
  },
  methods: {
    getImgPath(img) {
      return img ? new URL("../assets/images/" + img, import.meta.url) : "";
    },
  },
};
</script>
<style lang="scss" scoped>
.member-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;

  &__text {
    text-align: center;
    word-break: break-all;
    padding: 10px;
    font-size: 16px;
  }

  &__link {
    word-break: break-all;
  }

  &__img {
    width: 100%;
    height: 100%;
  }

  &__img-item {
    width: 100%;
    height: 100%;
  }

  &__column {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
  }
}
</style>
