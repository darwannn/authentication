import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
// import "bootstrap/dist/css/bootstrap.css";
// import bootstrap from "bootstrap/dist/js/bootstrap.js";
import { createPinia } from "pinia";
import "./index.css";
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { PrSpinner } from "oh-vue-icons/icons";

addIcons(PrSpinner);

const app = createApp(App);

app.component("v-icon", OhVueIcon);
app.use(router).use(createPinia()).mount("#app");
