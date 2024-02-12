<template>
    <div class="container my-5">
        <div class="col-md-6 offset-md-3">
            <div class="card card-outline-secondary">
                <div class="card-header">
                    <h3>New Password</h3>
                </div>
                <div class="alert alert-danger m-3" v-if="authStore.error_message">{{ authStore.error_message }}</div>
                <div class="alert alert-primary m-3" v-if="authStore.success_message">{{ authStore.success_message }}</div>
               
                <div class="card-body">
                    <form autocomplete="off" id="login" name="login" @submit.prevent="onSubmit">
                        <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model="form.password" type="password" class="form-control" id="password" name="password"
                                    placeholder="•••••••••••••">
                                <small class="text-danger">{{ error.password }}</small>
                            </div>
                        <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input v-model="form.password_confirmation" type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="•••••••••••••">
                                <small class="text-danger">{{ error.password_confirmation }}</small>
                            </div>
                        <button type="submit" class="btn btn-dark w-100 my-3"><span  v-if="isLoading">
                               Loading...
                            </span>
                            <span  v-else>
                                Change Password
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
    name:"new_password",
    setup() {
        const { cookies } = useCookies();
    
            const authStore = useAuthStore()
            return {authStore,cookies}
        
    },
    data() {
        return {
            isLoading:false,
            form: {
                password: '',
                password_confirmation: ''
            
            },
            error: {
              
                password: '',
                password_confirmation: ''
            },
        }
    },
    mounted() {
        this.onVefify();
    },
    unmounted(){
        this.authStore.error_message ='';
       
        
    },
    methods: {
        async onSubmit() {
            this.isLoading = true
            this.authStore.error_message =''
            console.log("submitted");
            const code = this.$route.params.code;
            const id = this.$route.params.id;
            await fetch(`http://127.0.0.1:8000/api/auth/new-password/${code}/${id}`, {
                    method: 'PUT',
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
                        this.$router.push('/login')
                        this.authStore.success_message = data.message;
                        this.resetForm();
                    }
                });
                this.isLoading = false;
        },
        async onVefify() {
           
            const code = this.$route.params.code;
            const id = this.$route.params.id;
            await fetch(`http://127.0.0.1:8000/api/auth/verify/${code}/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Access-Control-Allow-Origin': '*'
                    },
                   
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "error") {
                        this.$router.push('/login')
                    } 
                });
              
        },
        resetForm() {
           
            Object.keys(this.form).forEach(key => {
                this.form[key] = '';
            });
        
        }
    }
}
</script>

