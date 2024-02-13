<template>
    <section class="bg-[#F4F7FF] py-20 lg:py-[120px]">
        <div class="container">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4">
                    <div class="max-w-[525px] mx-auto  bg-white  rounded-lg relative overflow-hidden py-16 px-10 sm:px-12 md:px-[60px]
               ">
                        <div class="mb-10 md:mb-16  text-center">
                            <div class="inline-block max-w-[160px] mx-auto text-4xl font-semibold text-[#3056D3]">
                                Forgot Password
                            </div>
                        </div>

                        <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert" v-if="authStore.error_message">
                            <span class="block sm:inline">{{ authStore.error_message }}</span>
                        </div>
                        <div class="mb-8 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                            role="alert" v-if="authStore.success_message">
                            <span class="block sm:inline">{{ authStore.success_message }}</span>
                        </div>
                        <form autocomplete="off" id="login" name="login" @submit.prevent="onSubmit">
                            <div class="mb-6">
                                <input type="text" class=" w-full rounded-md  border border-[#E9EDF4] py-3 px-5 bg-[#FCFDFE] text-base text-body-color placeholder-[#ACB6BE] outline-none focus-visible:shadow-none focus:border-primary
                        " v-model="form.identifier" id="identifier" name="identifier"
                                    placeholder="Username or Email" /> <small
                                    class="text-left text-red-500">{{ error.identifier }}</small>
                            </div>
                            
                            <div class="mb-10">
                                <button type="submit" class="w-full rounded-md border border-[#3056D3] py-3 px-5 bg-[#3056D3] text-base text-white cursor-pointer hover:bg-opacity-90 transition
                        "><span v-if="isLoading">
                                        Loading...
                                    </span>
                                    <span v-else>
                                        Continue
                                    </span> </button>
                            </div>
                        </form>
                        <div class="text-center">


                            <p class="text-base text-[#adadad]">
                                Already have an account?
                                <a href="/login" class="text-primary hover:underline">
                                    Login
                                </a>
                            </p>
                           
                        </div>
                        <div>
                            <span class="absolute top-1 right-1">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="1.39737" cy="38.6026" r="1.39737"
                                        transform="rotate(-90 1.39737 38.6026)" fill="#3056D3" />
                                    <circle cx="1.39737" cy="1.99122" r="1.39737"
                                        transform="rotate(-90 1.39737 1.99122)" fill="#3056D3" />
                                    <circle cx="13.6943" cy="38.6026" r="1.39737"
                                        transform="rotate(-90 13.6943 38.6026)" fill="#3056D3" />
                                    <circle cx="13.6943" cy="1.99122" r="1.39737"
                                        transform="rotate(-90 13.6943 1.99122)" fill="#3056D3" />
                                    <circle cx="25.9911" cy="38.6026" r="1.39737"
                                        transform="rotate(-90 25.9911 38.6026)" fill="#3056D3" />
                                    <circle cx="25.9911" cy="1.99122" r="1.39737"
                                        transform="rotate(-90 25.9911 1.99122)" fill="#3056D3" />
                                    <circle cx="38.288" cy="38.6026" r="1.39737" transform="rotate(-90 38.288 38.6026)"
                                        fill="#3056D3" />
                                    <circle cx="38.288" cy="1.99122" r="1.39737" transform="rotate(-90 38.288 1.99122)"
                                        fill="#3056D3" />
                                    <circle cx="1.39737" cy="26.3057" r="1.39737"
                                        transform="rotate(-90 1.39737 26.3057)" fill="#3056D3" />
                                    <circle cx="13.6943" cy="26.3057" r="1.39737"
                                        transform="rotate(-90 13.6943 26.3057)" fill="#3056D3" />
                                    <circle cx="25.9911" cy="26.3057" r="1.39737"
                                        transform="rotate(-90 25.9911 26.3057)" fill="#3056D3" />
                                    <circle cx="38.288" cy="26.3057" r="1.39737" transform="rotate(-90 38.288 26.3057)"
                                        fill="#3056D3" />
                                    <circle cx="1.39737" cy="14.0086" r="1.39737"
                                        transform="rotate(-90 1.39737 14.0086)" fill="#3056D3" />
                                    <circle cx="13.6943" cy="14.0086" r="1.39737"
                                        transform="rotate(-90 13.6943 14.0086)" fill="#3056D3" />
                                    <circle cx="25.9911" cy="14.0086" r="1.39737"
                                        transform="rotate(-90 25.9911 14.0086)" fill="#3056D3" />
                                    <circle cx="38.288" cy="14.0086" r="1.39737" transform="rotate(-90 38.288 14.0086)"
                                        fill="#3056D3" />
                                </svg>
                            </span>
                            <span class="absolute left-1 bottom-1">
                                <svg width="29" height="40" viewBox="0 0 29 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="2.288" cy="25.9912" r="1.39737" transform="rotate(-90 2.288 25.9912)"
                                        fill="#3056D3" />
                                    <circle cx="14.5849" cy="25.9911" r="1.39737"
                                        transform="rotate(-90 14.5849 25.9911)" fill="#3056D3" />
                                    <circle cx="26.7216" cy="25.9911" r="1.39737"
                                        transform="rotate(-90 26.7216 25.9911)" fill="#3056D3" />
                                    <circle cx="2.288" cy="13.6944" r="1.39737" transform="rotate(-90 2.288 13.6944)"
                                        fill="#3056D3" />
                                    <circle cx="14.5849" cy="13.6943" r="1.39737"
                                        transform="rotate(-90 14.5849 13.6943)" fill="#3056D3" />
                                    <circle cx="26.7216" cy="13.6943" r="1.39737"
                                        transform="rotate(-90 26.7216 13.6943)" fill="#3056D3" />
                                    <circle cx="2.288" cy="38.0087" r="1.39737" transform="rotate(-90 2.288 38.0087)"
                                        fill="#3056D3" />
                                    <circle cx="2.288" cy="1.39739" r="1.39737" transform="rotate(-90 2.288 1.39739)"
                                        fill="#3056D3" />
                                    <circle cx="14.5849" cy="38.0089" r="1.39737"
                                        transform="rotate(-90 14.5849 38.0089)" fill="#3056D3" />
                                    <circle cx="26.7216" cy="38.0089" r="1.39737"
                                        transform="rotate(-90 26.7216 38.0089)" fill="#3056D3" />
                                    <circle cx="14.5849" cy="1.39761" r="1.39737"
                                        transform="rotate(-90 14.5849 1.39761)" fill="#3056D3" />
                                    <circle cx="26.7216" cy="1.39761" r="1.39737"
                                        transform="rotate(-90 26.7216 1.39761)" fill="#3056D3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>
<script>
import {useAuthStore } from '../../stores/auth'
import { useCookies } from "vue3-cookies";
export default {
    name:"forgot_password",
    setup() {
        const { cookies } = useCookies();
    
            const authStore = useAuthStore()
            return {authStore,cookies}
        
    },
    data() {
        return {
            isLoading:false,
            form: {
                identifier: '',
            
            },
            error: {
              
                identifier: '',
            
            },
        }
    },
    unmounted(){
        this.authStore.error_message ='';
        this.authStore.success_message ='';
        
    },
    methods: {
        async onSubmit() {
            this.isLoading = true
            this.authStore.error_message =''
            this.authStore.success_message =''
            console.log("submitted");
            await fetch("http://127.0.0.1:8000/api/auth/forgot-password", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Access-Control-Allow-Origin': '*'
                    },
                    body: JSON.stringify(this.form)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if ("errors" in data) {
                        Object.keys(this.error).forEach(key => {
                            this.error[key] = data.errors[key] || '';
                        });
                    } else {
                        Object.keys(this.error).forEach(key => {
                            this.error[key] = '';
                        });
                        if (data.status === "error") {
                            this.authStore.error_message = data.message;
                        } else {
                            this.authStore.success_message = data.message;
                            this.resetForm();
                        }
                    }
                });
                this.isLoading = false;
        },
        resetForm() {
           
            Object.keys(this.form).forEach(key => {
                this.form[key] = '';
            });
        
        }
    }
}
</script>

