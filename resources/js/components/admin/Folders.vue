<script>
import Spinner from 'vue-simple-spinner'

export default {
    name: "Folders",
    components: { Spinner},
    data(){
        return{
            folders:null,
            success:false,
            folderName : ""
        }
    },
    methods: {
        // createUser() {
        //     axios.post('/admin/create/staff', this.user).then(({data}) => {
        //         this.success = true
        //         this.errorData = {}
        //         this.error = false
        //         this.users.push(data);
        //         this.user = {
        //             ime: '',
        //             email: '',
        //             lozinka: '',
        //             role: 1,
        //         }
        //
        //     }).catch((error) => {
        //         if (error.response.status == 400) {
        //             this.errorData = error.response.data.original
        //             this.success = false
        //             this.error = false
        //         } else {
        //             this.error = true
        //             this.success = false
        //         }
        //     });
        // },

        addFolder(){
            if(this.folderName.trim() == '') {
                this.success = false
                return;
            }
            let similarValue = false;
            this.folders.forEach((data) => {
                if(data.name.toUpperCase() == this.folderName.trim().toUpperCase()){
                    similarValue = true;
                }
            })
            if(similarValue){
                return;
            }
            axios.post('/admin/create/folder', {folderName: this.folderName}).then(({data}) => {
                this.success = true
                this.folderName = "";
                this.getFolders();
            }).catch((error) => {
                this.success = false
                alert('POKUŠAJTE POSLE, DOŠLO JE DO GREŠKE')
            });

        },

        getFolders() {
            this.folders = null;
            axios.get('/admin/get/folders').then(({data}) => {
                this.folders = data

            })
        },
        deleteFolder(folder) {
            if(folder.name.toUpperCase() == "SVI FAJLOVI"){
                alert("Ne smete obrisati ovaj folder");
                return;
            }
            this.$confirm(
                {
                    message: 'DA LI STE SIGURNI DA ŽELITE DA OBRIŠETE FOLDER?',
                    button: {
                        no: 'NE',
                        yes: 'DA'
                    },
                    /**
                     * Callback Function
                     * @param {Boolean} confirm
                     */
                    callback: confirm => {
                        if (confirm) {
                            axios.delete('/admin/delete/folder/' + folder.id).then(({data}) => {
                               this.getFolders();
                            }).catch((error) => {
                                alert('POKUŠAJTE POSLE, DOŠLO JE DO GREŠKE')
                            });
                        }
                    }
                }
            )

        }
    },

    created() {
        this.getFolders();
    }
}
</script>

<template>
    <div><br><br>
        <vue-confirm-dialog></vue-confirm-dialog>

        <div class="container">
            <div class="row">


                <div class="col-lg-4 col-sm-12">
                    <div class="choose-type">
                        <div class="container">
                            <div class="row">
                                <div
                                    class="type-div-size type-div-size-back  bg-blue text-light pt-2 pb-2 d-flex align-items-center justify-content-center border-light-full">
                                    <span>DODAJ FOLDER</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="w-100 bg-personal-light">
                        <div class="container">
                            <div class="row">
                                <div class=" col-12 col-lg-12 col-md-12 col-sm-12  pt-3 pb-3">

                                    <div v-if="success"
                                         class="alert error-danger alert-success alert-dismissible fade show"
                                         role="alert">
                                        <span><i class="fa fa-check-square" aria-hidden="true"></i> &nbsp; FOLDER JE USPEŠNO DODAT</span>
                                        <button @click.prevent="success = false" type="button" class="close"  >
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <form action="">


                                        <div class="form-group search-font-size">
                                            <label for="name">NAZIV FOLDERA</label>
                                            <input type="text" name="name" v-model="folderName" id="name"
                                                   class="form-control" placeholder="NAZIV FOLDERA">
                                        </div>

                                        <div class="pt-2">
                                            <button class="btn btn-success w-100" @click.prevent="addFolder()">
                                                DODAJ <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>


                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="col-md-8 col-sm-12">
                    <table class="table  table-hover table-text-size table-cursor">
                        <thead class="bg-blue text-personal-light">
                        <tr>
                            <th>NAZIV FOLDERA</th>
                            <th>AKCIJA</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(folder , index) in folders" :key="index">
                            <td>{{ folder.name }}</td>
                            <td @click="deleteFolder(folder)"><i class="fa fa-trash text-danger"
                                    aria-hidden="true"></i></td>
                        </tr>
                        <tr v-if="folders  != null && folders.length < 1" class="bg-light">
                            <td colspan="2" class="text-center">
                                <span>NEMA PODATAKA ZA PRIKAZ</span>

                            </td>
                        </tr>
                        <tr v-if="folders == null" class="bg-light">
                            <td colspan="2" class="text-center">
                                <vue-simple-spinner></vue-simple-spinner>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
