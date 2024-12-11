<template>
    <div>
        <modal :name="'edit-files-modal'" @before-open="beforeOpenEdit" :shiftY="0.1" :scrollable="true"
               :adaptive="true"
               :resizable="true" height="auto">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-4  d-flex justify-content-end w-100 ">
                        <div class="text-right w-25 " @click="closeModal()">
                            <i class="fa fa-times fa-2x text-muted" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="add-type choose-type">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="choose-type w-100">
                                <div
                                    class="type-div-size bg-personal-light pt-2 pb-2 d-flex align-items-center justify-content-center border-light-full bg-blue text-light">
                                    <span>VANSUDSKI</span>
                                </div>
                                <div class="container bg-personal-light">
                                    <div class="row">
                                        <div class="col-12"><br>
                                            <vue-confirm-dialog></vue-confirm-dialog>
                                            <div class="w-100" v-if="caseData == null || loader">
                                                <vue-simple-spinner></vue-simple-spinner>
                                                <br>
                                            </div>
                                            <div v-if="success && !loader"
                                                 class="alert error-danger alert-success alert-dismissible fade show"
                                                 role="alert">
                                                <span><i class="fa fa-check-square" aria-hidden="true"></i> &nbsp; FAJLOVI SU USPEŠNO IZMENJENI</span>
                                                <button @click.prevent="success = false" type="button" class="close"

                                                >
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="" method="post" class="add-form-modal text-left"
                                                  v-if="caseData !== null " :hidden="loader">

                                                <div class="pb-3" >
                                                    <label>DOKUMENTI</label>
                                                    <div class="input-group custom-file-button">
                                                        <span class="input-group-text "><i class="fa fa-search-plus"
                                                                                           aria-hidden="true"></i></span>
                                                        <input type="file" multiple ref="file" class="form-control"
                                                               id="inputGroupFile">
                                                    </div>
                                                </div>
                                                <div class="pb-1 pt-4" >
                                                    <button class="btn btn-primary w-100" @click.prevent="editCase()">
                                                        DODAJ FAJLOVE &nbsp; <i
                                                        class="fa fa-spinner" aria-hidden="true"></i>
                                                    </button>
                                                </div>

                                                <br><br>
                                                <table
                                                    class="table mt-4  table-hover table-text-size table-cursor border-right-black">
                                                    <thead class="bg-blue text-personal-light">
                                                    <tr>
                                                        <th>IME FAJLA</th>
                                                        <th>DATUM</th>
                                                        <th>FAJL</th>
                                                        <th>OBRIŠI</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="bg-white " v-for="(file , key) in fileData" :key="key">
                                                        <td class="pb-0">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                       v-model="file.path"
                                                                       @keypress.enter="changeFilePath(file.id , file.path)">
                                                            </div>
                                                        </td>
                                                        <td>{{ file.date }}</td>
                                                        <td>
                                                            <a :href="file.name" download="" target="_blank"
                                                               rel="noopener noreferrer">
                                                                <i class="fa text-dark fa-file-text file-small-text"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                        <td @click.prevent="removeFile(file.id , key)">
                                                            <i class="fa fa-trash text-danger file-small-text"
                                                               aria-hidden="true"></i>
                                                        </td>
                                                    </tr>
                                                    <td v-if="fileData.length < 1" colspan="4"
                                                        class="text-center bg-light">
                                                        <span>NEMA PODATAKA ZA PRIKAZ</span>
                                                    </td>

                                                    </tbody>
                                                </table>


                                            </form>

                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </modal>
    </div>

</template>

<script>

import vSelect from "vue-select";
import {VueEditor} from "vue2-editor";
import DatePicker from 'vue2-datepicker';

export default {
    name: "EditFailes",
    props: ['type', 'case_types', 'institutionsSerchData' , 'is_admin'],
    data() {
        return {
            loader: true,
            data: [],
            caseID: '',
            caseData: null,
            fileData: [],
            caseIndex: -1,
            success: false,
            customToolbar: [
                [{header: [false, 1, 2, 3, 4, 5, 6]}],
                ["bold", "italic", "underline", "strike"],
                [{list: "ordered"}, {list: "bullet"}],
                [
                    {align: ""},
                    {align: "center"},
                    {align: "right"},
                    {align: "justify"}
                ],
                [{color: []}, {background: []}],
                [{indent: "-1"}, {indent: "+1"}],
            ]
        }
    },

    components: {
        'v-select': vSelect,
        VueEditor,
        DatePicker

    },

    mounted() {
        this.loader = true;
        this.success = false;

    },
    methods: {
        changeFilePath(id, path) {
            this.success = false;
            this.loader = true;
            axios.patch('/case/edit-file-path/' + id, {"path": path}).then(({data}) => {
                this.getCase();
                this.success = true;
            }).catch((error) => {
                alert('POKUŠAJTE POSLE, DOŠLO JE DO GREŠKE')
            })
        },

        editCase() {
            if (this.is_admin == 1) return false;

            this.loader = true;
            this.success = false;

            const formData = new FormData();
            for (var i = 0; i < this.$refs.file.files.length; i++) {
                let file = this.$refs.file.files[i];
                formData.append('files[' + i + ']', file);
            }

            axios.post('/case/files/upload/vansudski', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(({data}) => {
                this.getCase();
                this.success = true;

            }).catch((error) => {
                console.log(error);
            });


        },
        removeFile(fileId, index) {


            this.$confirm({
                message: 'DA LI STE SIGURNI DA ŽELITE DA OBRIŠETE FAJL?',
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
                        this.loader = true;
                        axios.delete('case/remove/file/' + fileId).then(({data}) => {
                            const filter = this.fileData.filter((value, key) => {
                                return key !== index
                            });
                            this.fileData = filter;
                            this.loader = false;
                        }).catch((error) => {
                            alert('POKUŠAJTE POSLE, DOŠLO JE DO GREŠKE')
                        });
                    }
                }
            })
        },

        getCase() {
            axios.get('/case/get/case/' + this.caseID).then(({data}) => {
                this.caseData = data.case
                this.caseData['case_type_id'] = this.type
                this.fileData = data.caseFiles
                this.loader = false;
            }).catch((error) => {
                alert('Došlo je do greške, probajte ponovo ili kontaktirajte administratora')
            })
        },
        beforeOpenEdit(event) {
            this.caseID = event.params.caseID;
            this.caseIndex = event.params.caseIndex;
            this.loader = true;
            this.success = false;
            this.getCase();
        },
        closeModal() {
            this.$modal.hide('edit-files-modal');
        }
    }
}
</script>

<style scoped lang="scss">
@import 'vue2-datepicker/index.css';
@import "vue-select/dist/vue-select.css";
@import "/public/css/style.css";
@import "/public/css/responsive.css";

.custom-file-button {
    input[type="file"] {
        margin-left: -2px !important;

        &::-webkit-file-upload-button {
            display: none;
        }

        &::file-selector-button {
            display: none;
        }
    }

    &:hover {
        span {
            background-color: #dde0e3;
            cursor: pointer;
        }
    }
}

.input-group-text {
    font-size: 12px;
}

</style>

