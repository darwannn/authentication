<template>
    <div class="container my-5">
        <div class="col-md-6 offset-md-3">
            <div class="card card-outline-secondary">
                <div class="card-header">
                    <h3>Forgot Password</h3>
                </div>
                <div class="alert alert-danger m-3" v-if="authStore.error_message">{{ authStore.error_message }}</div>
                <div class="alert alert-primary m-3" v-if="authStore.success_message">{{ authStore.success_message }}</div>
               
                <div class="card-body">
                    <form autocomplete="off" id="login" name="login" @submit.prevent="onSubmit">
                        <div class="form-group">
                            <label for="identifier">Username or Email</label>
                            <input v-model="form.identifier" type="text" class="form-control" id="identifier" name="identifier"
                                placeholder="juan / juan@laravel.com">
                            <small class="text-danger">{{ error.identifier }}</small>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 my-3"><span  v-if="isLoading">
                               Loading...
                            </span>
                            <span  v-else>
                                Continue
                            </span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                    }
                    if (data.status === "error") {
                        this.authStore.error_message = data.message;
                    } else {
                        this.authStore.success_message = data.message;
                        this.resetForm();
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

