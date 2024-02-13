import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
// import "bootstrap/dist/css/bootstrap.css";
// import bootstrap from "bootstrap/dist/js/bootstrap.js";
import { createPinia } from "pinia";
import "./index.css";
const app = createApp(App);

app.use(router).use(createPinia()).mount("#app");
