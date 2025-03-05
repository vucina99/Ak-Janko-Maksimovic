<script>
export default {
    name: "ClientFiles",
    props:['folders' , 'files' , 'case'],
    data(){
        return{
            selectedFolder: 1,
            allFiles: [],
            loader: false,
        }
    },
    methods : {
        clickFolder(){
            this.allFiles = [];
            this.loader = true;
            axios.get(`/case/get/files/${this.selectedFolder}/${this.case.id}`).then(({data}) => {
                this.allFiles = data
                this.loader = false;
            }).catch((error) => {
                console.log(error);
            })
        }
    },
    mounted() {
        this.folders.forEach((folder) => {
            if(folder.name.toUpperCase() == "SVI FAJLOVI"){
                this.selectedFolder = folder.id
            }
        });
        this.allFiles = this.files;
    }
}
</script>

<template>
    <div class="options-first options-first-lg  ">
        <div class="container">
            <div class="row">
                <div class="col-lg-4  col-sm-12 pb-5">

                    <div class="choose-type">
                        <div class="container">
                            <div class="row">
                                <div  v-for="(folder, index) in folders" @click.prevent="selectedFolder = folder.id ; clickFolder()"
                                     :class="[ 'type-div-size type-div-size-client w-100 bg-personal-light pt-2 pb-2 d-flex align-items-center justify-content-center border-light-full' , folder.id == selectedFolder ? 'bg-blue text-light' : '',]">
                                    <span>{{ folder.name.toUpperCase() }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12">
                    <table
                        class="table   table-hover table-text-size table-cursor border-right-black">
                        <thead class="bg-blue text-personal-light">
                        <tr>
                            <th>IME FAJLA</th>
                            <th>DATUM</th>
                            <th>FAJL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-white " v-for="(file , key) in allFiles" :key="key">
                            <td class="pb-0">
                                {{file.path}}
                            </td>
                            <td>{{ file.date }}</td>
                            <td>
                                <a :href="file.name" download="" target="_blank"
                                   rel="noopener noreferrer">
                                    <i class="fa text-dark fa-file-text file-small-text"
                                       aria-hidden="true"></i>
                                </a>
                            </td>

                        </tr>
                        <td v-if="allFiles.length < 1 && loader == false" colspan="3"
                            class="text-center bg-light">
                            <span>NEMA PODATAKA ZA PRIKAZ</span>
                        </td>
                        <td v-if="loader == true" colspan="3"
                            class="text-center bg-light">
                            <vue-simple-spinner></vue-simple-spinner>
                        </td>
                        </tbody>
                    </table>

                </div>

           </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
