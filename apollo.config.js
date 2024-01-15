// apollo.config.js
module.exports = {
  client: {
    service: {
      name: "my-app",
      url: `${import.meta.env.VITE_BASE_URL}/graphql`,
    },
    // Files processed by the extension
    includes: ["src/**/*.vue", "src/**/*.js"],
  },
};
