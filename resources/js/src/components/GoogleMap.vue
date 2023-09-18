<template>
  <div class="map-wrapper">
    <GoogleMap :api-key="apiKey" class="map" :center="center" :zoom="10">
      <Marker :options="markerOptions">
        <InfoWindow>
          <div class="info-window">
            <img
              src="../assets/images/7060-HOLLYWOOD.jpg"
              alt="7060 Hollywood Blvd, Los Angeles, CA"
              class="info-window__img"
            />
            <h4 class="info-window__title">
              7060 Hollywood Blvd, Los Angeles, CA
            </h4>
          </div>
        </InfoWindow>
      </Marker>
    </GoogleMap>
  </div>
</template>
<script>
import { GoogleMap, Marker, InfoWindow } from "vue3-google-map";

export default {
  components: {
    GoogleMap,
    Marker,
    InfoWindow,
  },
  setup() {
    const apiKey = import.meta.env.VITE_NEXT_PUBLIC_GOOGLE_MAPS_API_KEY;

    const center = { lat: 34.101585, lng: -118.333626 };
    const markerOptions = {
      position: center,
      title: "7060 Hollywood Blvd, Los Angeles, CA",
      label: "LA",
    };

    return {
      apiKey,
      center,
      markerOptions,
    };
  },
};
</script>
<style lang="scss" scoped>
.map-wrapper {
  width: 100%;
  height: 400px;
}
.map {
  width: 100%;
  height: 100%;
}
.info-window {
  width: 150px;

  &__img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    margin-bottom: 8px;
  }

  &__title {
    text-align: center;
    font-size: 12px;
  }
}
</style>
