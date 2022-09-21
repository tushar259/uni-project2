<template>

    <div style="width:100%;">
        <div class="form-horizontal" style="width:30%;margin-left:auto;margin-right:auto;">
            <fieldset>
                <div id="legend">
                    <legend class="">Login</legend>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="username">Email</label>
                    <div class="controls">
                        <input type="email" v-model="userLogin.email" placeholder="email" class="form-control">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="username">Password</label>
                    <div class="controls">
                        <input type="password" v-model="userLogin.password" placeholder="password" class="form-control">
                    </div>
                </div>
                <div style="height:20px;"></div>
                <div class="control-group">
                <!-- Button -->
                    <div class="controls">
                        <button class="btn btn-success" v-on:click="doLogin()">Login</button>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>



    <!-- <div>
        <div>
            email <input type="email" v-model="userLogin.email" placeholder="email">
            <br>
            password <input type="password" v-model="userLogin.password" placeholder="password">
        </div>
        <div>
            <button v-on:click="doLogin()">Login</button>
        </div>
    </div> -->
</template>

<script>
    export default {

        data(){
            return{
                userLogin: { 
                    'email': '',
                    'password': ''
                }
                
            }
        },

        methods: {

            doLogin(){
                localStorage.setItem('refresh', 0);
                if(this.userLogin.email == "" || this.userLogin.password == ""){
                    console.log("enter email and password");
                }
                else{
                    try{
                        axios.post('/api/usc/userLogin', this.userLogin).then((response) => {
                        this.$router.push('/');
                        
                    });
                    }
                    catch(e){
                        console.log(e);
                    }
                }
            }




        }


    }
</script>

