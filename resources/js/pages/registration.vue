<template>

    <div style="width:100%;">
        <div class="form-horizontal" style="width:30%;margin-left:auto;margin-right:auto;">
            <fieldset>
                <div id="legend">
                    <legend class="">Register</legend>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="username">Name</label>
                    <div class="controls">
                        <input type="text" v-model="userRegistration.name" placeholder="name" class="form-control">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="username">Email</label>
                    <div class="controls">
                        <input type="email" v-model="userRegistration.email" placeholder="email" class="form-control">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="username">Password</label>
                    <div class="controls">
                        <input type="password" v-model="userRegistration.password" placeholder="password" class="form-control">
                    </div>
                </div>
                <div style="height:20px;"></div>
                <div class="control-group">
                <!-- Button -->
                    <div class="controls">
                        <button class="btn btn-success" v-on:click="doRegister()">Create Account</button>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>


    <!-- <div>
        <div>
            Name <input type="text" v-model="userRegistration.name" placeholder="name">
            <br>
            Email <input type="email" v-model="userRegistration.email" placeholder="email">
            <br>
            Password <input type="password" v-model="userRegistration.password" placeholder="password">
        </div>
        <div>
            <button v-on:click="doRegister()">Create Account</button>
        </div>
    </div> -->
</template>

<script>
    export default {

        data(){
            return{
                userRegistration: {
                    'name': '', 
                    'email': '',
                    'password': ''
                }
                
            }
        },

        methods: {

            doRegister(){
                if(this.userRegistration.name == "" || this.userRegistration.email == "" || this.userRegistration.password == ""){
                    console.log("enter name, email and password");
                }
                else{
                    try{
                        axios.post('/api/usc/userReg', this.userRegistration).then((response) => {
                        //this.$swal('Account Created');
                        this.$swal(
                            'Congratulations',
                            'Account Created',
                            'success'
                        )
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





