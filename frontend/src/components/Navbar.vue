<template>
<div class="w-full text-gray-700 bg-white dark-mode:text-gray-200 dark-mode:bg-gray-800">
  <div x-data="{ open: false }" class="flex flex-col max-w-screen-xl px-4 mx-auto md:items-center md:justify-between md:flex-row md:px-6 lg:px-8">
    <div class="p-4 flex flex-row items-center justify-between">
      <a href="/" class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">Authentication</a>
    </div>
    <nav class="flex-col flex-grow pb-4 md:pb-0 hidden md:flex md:justify-end md:flex-row gap-2">
     <RouterLink to="/" v-if="authStore.is_authenticated" @click="onLogout">
       <a active-class="bg-gray-200" class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">Logout</a>
     </RouterLink>
     <RouterLink to="/login" v-if="!authStore.is_authenticated">
       <a active-class="bg-gray-200" class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">Login</a>
     </RouterLink>
     <RouterLink to="/register" v-if="!authStore.is_authenticated">
       <a class="px-4 py-2 mt-2 text-sm font-semibold rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">Register</a>
    </RouterLink>
    </nav>
  </div>
</div>
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
    async onLogout() {
      await fetch(`http://127.0.0.1:8000/api/auth/logout`, {
                    method: 'POST',
                    headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Access-Control-Allow-Origin': '*',
                            'Authorization': `Bearer ${this.cookies.get('token')}`
                        }
                  
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === "error") {
                        console.log("error:", data.message);
                    } else {
                        this.$router.push('/login')
                        console.log("success:", data.message);
                        this.cookies.remove("token");
      this.authStore.set_authentication(false);
                    }
                });
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