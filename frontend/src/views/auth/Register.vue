    <template>
        <div class="container my-5">
            <div class="col-md-6 offset-md-3">
                <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3>Register</h3>
                    </div>
                    <div class="alert alert-danger m-3" v-if="authStore.error_message != ''">{{ authStore.error_message }}</div>
                    <div class="alert alert-primary m-3" v-if="authStore.success_message != ''">{{ authStore.success_message }}</div>
                    <div class="card-body">
                        <form autocomplete="off" id="register" name="register" @submit.prevent="onSubmit">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input v-model="form.first_name" type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="Juan">
                                    <small class="text-danger">{{ error.first_name }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input v-model="form.last_name" type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Dela Cruz">
                                    <small class="text-danger">{{ error.last_name }}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input v-model="form.username" type="text" class="form-control" id="username" name="username"
                                    placeholder="juan">
                                <small class="text-danger">{{ error.username }}</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="form.email" type="text" class="form-control" id="email" name="email"
                                    placeholder="juan@laravel.com">
                                <small class="text-danger">{{ error.email }}</small>
                            </div>
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
                            <button type="submit" class="btn btn-dark w-100 my-3" :disabled="isLoading">
                                <span  v-if="isLoading">
                                Loading...
                                </span>
                                <span  v-else>
                                    Register
                                </span>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
    import {useAuthStore } from '../../stores/auth'
    
    export default {
        name:"register",
        setup() {
            
            const authStore = useAuthStore()
            return {authStore}
        
    },
    unmounted(){
        this.authStore.error_message ='';
        this.authStore.success_message ='';
        
    },
        data() {
            return {
                isLoading:false,
                form: {
                    first_name: '',
                    last_name: '',
                    username: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                error: {
                    message: '',
                    first_name: '',
                    last_name: '',
                    username: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                success:{
                    message:''
                }
            }
        },
        methods: {
            async onSubmit() {
                this.isLoading = true;
                this.authStore.error_message =''
                console.log("submitted");
                await fetch("http://127.0.0.1:8000/api/auth/register", {
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
                                this.authStore.error_message =''
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


