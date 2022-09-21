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
                        <th scope="col">Request To Unblock</th>
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
                        <td><textarea v-if="singleUpload.active_status == 'blocked'" v-model="reasonToUnblock" @keyup.enter="enterButtonPressed(singleUpload.id)" placeholder="Describe your reason to unblock and press enter"></textarea></td>
                        
                    </tr>
                
                </tbody>
            </table>
        </div>
    </div>

    <!-- <div>

        <div>
            <input type="file" ref="file" multiple="multiple">
            <button v-on:click="uploadClicked()">upload</button>
        </div>
        <div v-for="(singleUpload, index) in uploadedFiles">
            {{singleUpload.name}}
            {{singleUpload.size}}
            {{singleUpload.uploaded_time}}
            {{singleUpload.last_downloaded}}
            {{singleUpload.active_status}}
            <a v-if="singleUpload.active_status == 'active'" v-bind:href="singleUpload.location" :download="singleUpload.name" style="text-decoration: none !important;" @click="downloadedFile(singleUpload.id)">Download</a>
            <a v-if="singleUpload.active_status == 'blocked'" :disabled="isDisabled" style="text-decoration: none !important;">Download</a>
            <textarea v-if="singleUpload.active_status == 'blocked'" v-model="reasonToUnblock" @keyup.enter="enterButtonPressed(singleUpload.id)" placeholder="Describe your reason to unblock and press enter"></textarea>
        </div>
    </div> -->
</template>

<script>
export default {

    data(){
        return{
            uploadedFiles: [],
        }
    },

    mounted(){
        //this.getUserData();
        this.getUploadedFiles();
    },

    methods: {

        getUploadedFiles(){
            try{
                axios.get('/api/home/getLoggedUploadedFiles').then((response) => {
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
            formData.append('reason', this.reasonToUnblock);
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




