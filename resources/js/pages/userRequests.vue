<template>
    <div style="width:100%">

        <div style="width:80%;margin-left:auto;margin-right:auto;">
            <table class="table table-hover" style="">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Size</th>
                        <th scope="col">Uploaded Date</th>
                        <th scope="col">Last Downloaded</th>
                        <th scope="col">Active Status</th>
                        <th scope="col">Requested Reason</th>
                        <th scope="col">Action</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(singleUpload, index) in uploadedFiles">
                        <td>{{singleUpload.name}}</td>
                        <td>{{singleUpload.size}}</td>
                        <td>{{singleUpload.uploaded_time}}</td>
                        <td>{{singleUpload.last_downloaded}}</td>
                        <td>{{singleUpload.active_status}}</td>
                        <td>{{singleUpload.request_reason}}</td>
                        <td><button v-on:click="doAction(singleUpload.id,'active')" class="btn btn-primary">Activate</button></td>
                        <td><button v-on:click="doAction(singleUpload.id, 'blocked')" class="btn btn-danger">Decline</button></td>
                        
                    </tr>
                
                </tbody>
            </table>
        </div>
    </div>

    <!-- <div>
        <div v-for="(singleUpload, index) in uploadedFiles">
            {{singleUpload.name}}
            {{singleUpload.size}}
            {{singleUpload.uploaded_time}}
            {{singleUpload.last_downloaded}}
            {{singleUpload.active_status}}
            {{singleUpload.request_reason}}
            <button v-on:click="doAction(singleUpload.id,'active')">Activate</button>
            <button v-on:click="doAction(singleUpload.id, 'blocked')">Decline</button>
        </div>
    </div> -->
</template>

<script>
export default{

    data(){
        return{
            uploadedFiles: [],
        }
    },

    mounted(){
        this.getRequestedFiles();
    },

    methods: {

        doAction(fileId, value){
            let formData = new FormData();
            formData.append('fileId', fileId);
            formData.append('value', value);
            try{
                axios.post('/api/home/updateRequestedFiles', formData).then((response) => {
                if(response.data.message){
                    console.log(response);
                }
                this.getRequestedFiles();
            });
            }
            catch(e){
                console.log(e);
            }
        },

        getRequestedFiles(){
            try{
                axios.get('/api/home/getRequestedFiles').then((response) => {
                if(response.data.message == "success"){
                    this.uploadedFiles = response.data.data;
                }
                
            });
            }
            catch(e){
                console.log(e);
            }
        },
    }
}
</script>





