<template>
    <div>
        <div class="flex flex-col bg-gray-200 p-5 gap-3 rounded-lg">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-left flex-1">Notification</h1>
                <span class="text-xs text-left cursor-pointer" @click="onReadAll">Mark all as read</span>
            </div>
            <div v-for="notification in notifications" :key="notification.data.id"
                v-bind:class="[notification.read_at === null ? 'border-blue-200' : 'border-white', 'bg-white', 'shadow-md', 'rounded-md', 'p-2', 'border-l-4']">
                <div class="flex items-end">
                    <a :href="[notification.data.id !== null && `http://localhost:5173/${notification.data.id}`]"
                        target="_blank" class="">

                        <p class="text-left  text-xs">{{ dateTime(notification.created_at) }}</p>
                        <p class="text-left  text-lg font-semibold">{{ notification.data.title }}</p>
                        <p class="text-left text-sm">{{ notification.data.message }}</p>

                    </a>
                    <div class="ml-auto text-xs cursor-pointer" @click="onUpdate(notification.data.id)">
                        <div v-if="notification.read_at === null">
                            Read
                        </div>
                        <div v-else>
                            Unread
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
import moment from 'moment';
import {
    useAuthStore
} from '../stores/auth'
import {
    useCookies
} from "vue3-cookies";
export default {
    name: "notifcation",
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
    data() {
        return {

            notifications: []
        };
    },
    mounted() {
        this.getMyNotification();
    },
    methods: {
        dateTime(value) {
            return moment(value).format('YYYY-MM-DD');
        },
        getMyNotification() {

            fetch("http://127.0.0.1:8000/api/notification/", {
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

                        this.notifications = data.data.notifications;
                        console.log("Response:", data);
                        console.log(this.notifications);
                    }
                })

        },

        onUpdate(id) {
            console.log(id)
            fetch(`http://127.0.0.1:8000/api/notification/update/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.cookies.get('token')}`
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status == "success") {
                        this.getMyNotification()
                    }
                })

        },
        onReadAll(id) {
            console.log(id)
            fetch(`http://127.0.0.1:8000/api/notification/update/`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.cookies.get('token')}`
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status == "success") {
                        this.getMyNotification()
                    }
                })

        },

    }
};
</script>

<style scoped></style>