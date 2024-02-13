
<script>
import {useAuthStore } from '../../stores/auth'
export default {
    name:"activate",
    setup() {
        
        const authStore = useAuthStore()
        return {authStore}
    
},
    mounted() {
        this.onVerify()
    },
    methods: {
        async onVerify() {
         
            console.log("submitted");
            const token = this.$route.params.token;
            const email = this.$route.params.email;
          
        
            await fetch(`http://127.0.0.1:8000/api/auth/activate/${token}/${email}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Access-Control-Allow-Origin': '*'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                  
                    if (data.status === "error") {
                     
                    } else {
                        
                        this.authStore.success_message = data.message;
                    }
                   

                    this.$router.push('/login')
                });
                
        },
        
    }
}
</script>

