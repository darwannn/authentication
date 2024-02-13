<template>
    <div class="text-center">

        <div   v-if="authStore.is_authenticated">
            <h1>Welcome {{ user.first_name }} {{ user.last_name }}</h1>
            
            <p>Email: {{ user.email }}</p>
            <p>Username: {{ user.username }}</p>
        </div>
        <div  v-else >
            <h1 >Please Login to view this page</h1>
        </div>
    </div>
</template>

<script >
import { useAuthStore } from '../stores/auth'
import { useCookies } from "vue3-cookies";
import {
    RouterLink
} from 'vue-router';
export default {
    name: "home",
    setup() {

        const { cookies } = useCookies();

        const authStore = useAuthStore()
        return { authStore, cookies }

    },
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                email: '',
                username: ''
            }


        };
    },
    mounted() {
        this.getMyData();
    },
    methods: {
        getMyData() {
            console.log("Fetching data...");
            console.log("Token:", this.cookies.get('token'));
            fetch("http://127.0.0.1:8000/api/me", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                    'Authorization': `Bearer ${this.cookies.get('token')}`
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status == "success") {
                        this.user.first_name = data.data.user.first_name;
                        this.user.last_name = data.data.user.last_name;
                        this.user.email = data.data.user.email;
                        this.user.username = data.data.user.username;
                        console.log("Response:", data);
                    }
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                });
        },

    }
};

</script>

<style scoped>

</style>
