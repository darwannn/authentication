import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/auth/Login.vue";
import Register from "../views/auth/Register.vue";
import Home from "../views/Home.vue";
import Activate from "../views/auth/Activate.vue";
import ForgotPassword from "../views/auth/ForgotPassword.vue";
import NewPassword from "../views/auth/NewPassword.vue";


const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: Home,
        },
        {
            path: "/register",
            name: "register",
            component: Register,
        },
        {
            path: "/login",
            name: "login",
            component: Login,
        },
        {
            path: "/activate/:code/:id",
            name: "activate",
            component: Activate,
        },
        {
            path: "/forgot-password",
            name: "forgot_password",
            component: ForgotPassword,
        },
        {
            path: "/new-password/:code/:id",
            name: "new_password",
            component: NewPassword,
        },
    ],
});

export default router;
