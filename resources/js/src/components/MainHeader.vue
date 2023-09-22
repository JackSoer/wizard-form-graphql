<template>
  <header class="header">
    <nav class="nav">
      <ul class="nav__list">
        <li class="nav__list-item">
          <router-link to="/admin-table">Admin Table</router-link>
        </li>
        <li class="nav__list-item">
          <router-link to="/all-members">All Members</router-link>
        </li>
      </ul>
    </nav>
  </header>
</template>
<script>
export default {};
</script>
<style lang="scss" scoped>
@import "../../../scss/var.scss";
.header {
  height: 60px;
  width: 100%;
  background-color: $mainTextColor;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  top: 0;
  left: 0;
  right: 0;
}

.nav {
  &__list {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;

    &-item {
      a {
        color: yellow;
        font-size: 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;

        &:hover {
          color: orange;
        }
      }
    }

    .router-link-active {
      &:hover:after {
        background-color: orange;
      }

      &:after {
        content: "";
        display: inline-block;
        background-color: yellow;
        height: 3px;
        width: 100%;
        position: absolute;
        left: 0;
        bottom: -7px;
        transition: all 0.3s ease;
      }
    }
  }
}
</style>
