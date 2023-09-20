import "./bootstrap";
import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router/router";
import components from "@/components/UI";
import store from "@/store";
import VueSocialSharing from "vue-social-sharing";

const app = createApp(App);

components.map((component) => app.component(component.name, component));

app.use(router).use(store).use(VueSocialSharing).mount("#app");
