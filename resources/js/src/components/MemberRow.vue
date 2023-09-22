<template>
  <tr
    class="member-row"
    :class="{ 'member-row--admin': $route.path === '/admin-table' }"
    v-if="member.isVisible || $route.path === '/admin-table'"
  >
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
    <td class="member-row__column" v-if="$route.path === '/admin-table'">
      <div class="member-row__btns">
        <button
          class="member-row__btn"
          aria-label="edit"
          @click="$router.push(`/edit-member/${member.id}`)"
        >
          <img
            src="../assets/images/pen.png"
            alt="edit"
            class="member-row__btn-icon"
          />
        </button>
        <button
          class="member-row__btn"
          :aria-label="member.isVisible ? 'View' : 'Hide'"
          @click="this.$emit('editVisibility', member)"
        >
          <img
            :src="
              member.isVisible ? getImgPath('view.png') : getImgPath('hide.png')
            "
            :alt="member.isVisible ? 'View' : 'Hide'"
            class="member-row__btn-icon"
          />
        </button>
        <button
          class="member-row__btn"
          aria-label="delete"
          @click="this.$emit('remove', member.id)"
        >
          <img
            src="../assets/images/delete.png"
            alt="delete"
            class="member-row__btn-icon"
          />
        </button>
      </div>
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

  &--admin {
    grid-template-columns: 1fr 1fr 1fr 1fr 90px;
  }

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

  &__btns {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 15px;
  }

  &__btn {
    width: 40px;
    height: 40px;

    &-icon {
      width: 100%;
      height: 100%;
    }
  }
}
</style>
