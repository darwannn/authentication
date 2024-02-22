<template>
    <div>
        <div class="h-screen w-screen bg-gray-100 flex flex-col items-center justify-center gap-3">
            <div v-if="authStore.is_authenticated">
                <div
                    class="relative mt-6 flex w-96 flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md text-center">
                    <!-- <div class="p-6">
                        <h5
                            class="mb-2 block font-sans text-xl font-semibold leading-snug tracking-normal text-blue-gray-900 antialiased">
                            {{ user.first_name }} {{ user.last_name }}
                        </h5>
                        <div>@{{ user.username }}</div>
                        <div>{{ user.email }}</div>
                    </div> -->
                    <NotificationPanel />
                </div>

            </div>
            <div v-else>
                <div class="text-xl font-semibold">Please <a href="/login" class="underline text-[#3056D3]">login</a> to
                    view this page</div>
            </div>
        </div>
    </div>

</template>

<script>
    import NotificationPanel from '../components/NotificationPanel.vue'
    import {
        useAuthStore
    } from '../stores/auth'
    import {
        useCookies
    } from "vue3-cookies";
    export default {
        name: "home",
        setup() {

            const {
                cookies
            } = useCookies();

            const authStore = useAuthStore()
            return {
                authStore,
                cookies
            }

        },
        components: {
            NotificationPanel
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
                fetch("http://127.0.0.1:8000/api/account/me", {
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