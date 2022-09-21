<template>
    <div>
        <nav class="navbar navbar-light">
            <router-link to="/" class="navbar-brand mb-0 h1" style="text-decoration: none !important;">Home</router-link> 
            <div class="navbar-brand mb-0 h1" style="float:right;" v-if="loggedInUser.type == 'user'">
                {{loggedInUser.name}} | 
                <router-link to="/myuploads" style="text-decoration: none !important;">My Uploads</router-link> |
                <a style="text-decoration: none !important;" href="" v-on:click="logoutPressed()">Log out</a>
            </div>
            <div class="navbar-brand mb-0 h1" style="float:right;" v-else-if="loggedInUser.type == 'admin'">
                {{loggedInUser.name}} | 
                <router-link to="/userrequests" style="text-decoration: none !important;">User requests</router-link> | 
                <a style="text-decoration: none !important;" href="" v-on:click="logoutPressed()">Log out</a>
            </div>
            <div class="navbar-brand mb-0 h1" style="float:right;" v-else>
                <router-link to="/login" style="text-decoration: none !important;">Login</router-link> | <router-link style="text-decoration: none !important;" to="registration">Registration</router-link>
            </div>
        </nav>
        <!-- <div>
            <router-link to="/" style="text-decoration: none !important;">Home</router-link>  
            <div v-if="loggedInUser.type == 'user'">
                {{loggedInUser.name}}
                <router-link to="/myuploads" style="text-decoration: none !important;">My Uploads</router-link> |
                <a style="text-decoration: none !important;" href="" v-on:click="logoutPressed()">Log out</a>
            </div>
            <div v-else-if="loggedInUser.type == 'admin'">
                {{loggedInUser.name}}
                <router-link to="/userrequests" style="text-decoration: none !important;">User requests</router-link> | 
                <a style="text-decoration: none !important;" href="" v-on:click="logoutPressed()">Log out</a>
            </div>
            <div v-else>
                <router-link to="/login" style="text-decoration: none !important;">Login</router-link> | <router-link style="text-decoration: none !important;" to="registration">Registration</router-link>
            </div>
        </div> -->
        
        <router-view></router-view>
    </div>
</template>

<script>
export default{
    
    data(){
        return{
            loggedInUser:{
                'id': '',
                'name': '',
                'email': '',
                'type': ''
            },
        }

    },

    mounted(){
        this.getUserData();
        //this.loadThispage();
    },

    methods: {

        loadThispage(){
            var refresh = localStorage.getItem('refresh');
            if(refresh == null || refresh == ""){
                localStorage.setItem('refresh', "1");
                location.reload();
            }
        },

        getUserData(){
            try{
                axios.get('/api/home/ifUserLoggedIn').then((response) => {
                if(response.data.message == "success"){
                    this.loggedInUser.id = response.data.data.userid;
                    this.loggedInUser.name = response.data.data.name;
                    this.loggedInUser.email = response.data.data.email;
                    this.loggedInUser.type = response.data.data.type;
                }

                console.log(response.data.data.type);
            });
            }
            catch(e){
                console.log(e);
            }
        },

        logoutPressed(){
            localStorage.setItem('refresh', 0);
            try{
                axios.get('/api/usc/logout').then((response) => {
                
                    this.$router.push('/');

            });
            }
            catch(e){
                console.log(e);
            }
            this.$router.push('/');
        },

        

    }
}
</script>
