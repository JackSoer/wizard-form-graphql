import "./bootstrap";
import { createApp, provide, h } from "vue";
import App from "@/App.vue";
import router from "@/router/router";
import components from "@/components/UI";
import store from "@/store";
import VueSocialSharing from "vue-social-sharing";
import { ApolloClient, InMemoryCache } from "@apollo/client/core";
import { DefaultApolloClient } from "@vue/apollo-composable";
import createUploadLink from "apollo-upload-client/createUploadLink.mjs";

const httpLink = createUploadLink({
  uri: `http://127.0.0.1:8000/graphql`,
});

const cache = new InMemoryCache();

const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
});

const app = createApp({
  setup() {
    provide(DefaultApolloClient, apolloClient);
  },

  render: () => h(App),
});

components.map((component) => app.component(component.name, component));

app.use(router).use(store).use(VueSocialSharing).mount("#app");
