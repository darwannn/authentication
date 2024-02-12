<template>
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <RouterLink class="navbar-brand text-dark" to="/">Vue & Laravel</RouterLink>

      <div id="navbarNav">
        <ul class="navbar-nav">
          <li v-if="authStore.is_authenticated" class="nav-item" @click="onLogout">
            <RouterLink class="nav-link text-dark" to="/">Logout</RouterLink>
          </li>
          <li v-if="!authStore.is_authenticated" class="nav-item">
            <RouterLink class="nav-link text-dark" to="/login">Login</RouterLink>
          </li>
          <li v-if="!authStore.is_authenticated" class="nav-item">
            <RouterLink class="nav-link text-dark" to="/register">Register</RouterLink>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router';
import { useAuthStore } from '../stores/auth'
import { useCookies } from "vue3-cookies";
export default {


  setup() {
    const { cookies } = useCookies();

    const authStore = useAuthStore()
    return { authStore, cookies }

  },
  data() {
    return {
      authStore: useAuthStore(),
      cookies: useCookies(),
    };
  },
  mounted() {
    this.checkAuthentication();
  },
  methods: {
    onLogout() {
      this.cookies.remove("token");
      this.authStore.set_authentication(false);
    },
    checkAuthentication() {
      const token = this.cookies.get('token');
      if (token) {
       
        this.authStore.set_authentication(true);
      } else {
        console.log("No token found.");
      }
    }
  },
  watch: {
    '$route': 'checkAuthentication' 
  }
};
</script>

<style scoped></style>