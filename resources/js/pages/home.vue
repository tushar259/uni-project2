<template>
    <div style="width:100%">

        
        <div class="mb-3" style="width:80%;margin-left:auto;margin-right:auto;">
            <input class="form-control" style="width:30%;float:left;" id="formFileMultiple" type="file" ref="file" multiple="multiple">
            <!-- <div style="height:5px;"></div> -->
            <button class="btn btn-secondary" v-on:click="uploadClicked()">Upload</button>
        </div>
        <div style="height:40px;"></div>
        <div style="width:80%;margin-left:auto;margin-right:auto;">
            <table class="table table-hover" style="">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Size</th>
                        <th scope="col">Uploaded Date</th>
                        <th scope="col">Last Downloaded</th>
                        <th scope="col">Active Status</th>
                        <th scope="col">Operation</th>
                        <th scope="col" v-if="loggedInUser.type!='admin'">Request To Unblock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(singleUpload, index) in uploadedFiles">
                        <td>{{singleUpload.name}}</td>
                        <td>{{singleUpload.size}}</td>
                        <td>{{singleUpload.uploaded_time}}</td>
                        <td>{{singleUpload.last_downloaded}}</td>
                        <td>{{singleUpload.active_status}}</td>
                        <td v-if="singleUpload.active_status == 'active'"><a v-bind:href="singleUpload.location" :download="singleUpload.name" style="text-decoration: none !important;" @click="downloadedFile(singleUpload.id)">Download</a></td>
                        <td v-if="singleUpload.active_status == 'blocked'"><a style="text-decoration: none !important;color:red;" >Download disabled</a></td>
                        <td v-if="singleUpload.active_status == 'request to active'"><a style="text-decoration: none !important;color:red;" >Download disabled</a></td>
                        <td v-if="loggedInUser.type!='admin'"><textarea v-if="singleUpload.active_status == 'blocked'" v-bind:id="'reasonToUnblock'+singleUpload.id" @keyup.enter="enterButtonPressed(singleUpload.id)" placeholder="Describe your reason to unblock and press enter"></textarea></td>
                        
                    </tr>
                
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {

    data(){
        return{
            reasonToUnblock: '',
            uploadedFiles: [],
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
        this.loadThispage();
        this.getUploadedFiles();
    },

    methods: {

        loadThispage(){
            var refresh = localStorage.getItem('refresh');
            
            if(refresh != 1){
                console.log("refresh: ");
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
                
            });
            }
            catch(e){
                console.log(e);
            }
        },

        getUploadedFiles(){
            try{
                axios.get('/api/home/getUploadedFiles').then((response) => {
                if(response.data.message == "success"){
                    this.uploadedFiles = response.data.data;
                }
                
            });
            }
            catch(e){
                console.log(e);
            }
        },

        uploadClicked(){
            let formData = new FormData();

            for( var i = 0; i < this.$refs.file.files.length; i++ ){
                let file = this.$refs.file.files[i];
                formData.append('files[' + i + ']', file);
            }

            try{
                axios.post( '/api/home/uploadFiles',
                    formData,
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function( progressEvent ) {
                        //this.introVideoUploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));
                    }.bind(this)
                }).then((response)=>{
                    this.getUploadedFiles();
                        
                })
                .catch(()=>{
                    this.getUploadedFiles();
                });
            }
            catch(e){
                this.getUploadedFiles();
            }

        },

        enterButtonPressed(value){
            let formData = new FormData();
            formData.append('fileId', value);
            formData.append('reason', $('#reasonToUnblock'+value).val());
            if(value != ""){
                try{
                    axios.post('/api/home/enterReason', formData).then((response) => {
                        if(response.data.message == "success"){
                            this.getUploadedFiles();
                        }
                    
                });
                }
                catch(e){
                    console.log(e);
                }
            }
        },

        downloadedFile(fileId){
            try{
                axios.post('/api/home/fileDownloaded/'+fileId).then((response) => {
                    this.getUploadedFiles();
                
            });
            }
            catch(e){
                console.log(e);
            }
        },

    }
}
</script>