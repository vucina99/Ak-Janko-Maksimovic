<template>
    <div class="options-first options-first-lg">
        <vue-confirm-dialog></vue-confirm-dialog>
        <edit-files :type="type" :is_admin="is_admin"></edit-files>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="choose-type">
                        <div
                            class="type-div-size bg-personal-light pt-2 pb-2 d-flex align-items-center justify-content-center border-light-full bg-blue text-light">
                            <span>VANSUDSKI</span>
                        </div>
                    </div>
                    <div class="w-100 bg-personal-light pt-5 pr-3 pl-3">
                        <form action="">
                            <div class="row">
                                <!-- Polja za pretragu -->

                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="number_office">BROJ U KANCELARIJI</label>
                                        <input type="text" name="number_court" v-model="search.number_office"
                                               id="number_office" class="form-control" placeholder="BROJ U KANCELARIJI">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="number_office">OŠTEĆENI</label>
                                        <v-select :options="person_1_list" :id="'prosecutor'" label="prosecutor"
                                                  v-model="search.person_1" placeholder="OŠTEĆENI"></v-select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="mark">REGISTASKE OZNAKE</label>
                                        <input type="text" name="mark" id="mark" v-model="search.mark"
                                               class="form-control" placeholder="REGISTASKE OZNAKE">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="number_office">STATUS</label>
                                        <input type="text" name="number_court" id="number_court"
                                               v-model="search.status" class="form-control"
                                               placeholder="BROJ U OSIGURANJU">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="datumSlikanjaOd">DATUM SLIKANJA OD</label>
                                        <date-picker v-model="search.datumSlikanjaOd"
                                                     id="datumSlikanjaOd"
                                                     :format="'DD-MM-YYYY'"
                                                     :placeholder="search.datumSlikanjaOd ? JSON.stringify(search.datumSlikanjaOd)  : 'DATUM SLIKANJA OD'"></date-picker>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="datumSlikanjaDo">DATUM SLIKANJA DO</label>
                                        <date-picker v-model="search.datumSlikanjaDo"
                                                     id="datumSlikanjaDo"
                                                     :format="'DD-MM-YYYY'"
                                                     :placeholder="search.datumSlikanjaDo ? JSON.stringify(search.datumSlikanjaDo)  : 'DATUM SLIKANJA DO'"></date-picker>
                                    </div>
                                </div>

                            </div>
                            <div class="pt-3 pr-1 pl-1 pb-3">
                                <div class="w-100 d-flex justify-content-between">
                                    <button class="btn btn-primary w-50 mr-1" @click.prevent="getCase()">PRETRAGA <i
                                        class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-primary ml-1 w-50" @click.prevent="resetFilter()">RESETUJ <i
                                        class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-sm-12 margin-top-table pr-3 pl-3">
                    <button v-if="is_admin == 2" class="btn btn-success px-5" @click="addNewCase()">DODAJ <i class="fa fa-plus"
                                                                                        aria-hidden="true"></i></button>
                    <div class="table-responsive">
                        <table class="table table-hover table-text-size table-cursor">
                            <thead class="bg-blue text-personal-light">
                            <tr>
                                <th scope="col">BR.</th>
                                <th scope="col">BR. KANC</th>
                                <th scope="col">REG OZNAKE</th>
                                <th scope="col">MARKA I MODEL</th>
                                <th scope="col" class="first_last_name">IME I PREZIME</th>
                                <th scope="col">DATUM SLIKANJA</th>
                                <th scope="col">DATUM SLANJA VEŠTAKU</th>
                                <th scope="col">DATUM SLANJA NA MAIL</th>
                                <th scope="col">DATUM NALAZA</th>
                                <th scope="col">T. VEŠTAK</th>
                                <th scope="col">DATUM PRIJAVE OS.</th>
                                <th scope="col">ROK</th>
                                <th scope="col">TRAŽENI IZNOS</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">BELEŠKA MUP</th>
                                <th scope="col">BROJ ŠTETE</th>
                                <th scope="col">ISPLAĆEN IZNOS</th>
                                <th scope="col">PROVIZIJA</th>
                                <th scope="col">AT</th>
                                <th scope="col">TUŽBA</th>
                                <th scope="col" class="not">NAPOMENA</th>
                                <th scope="col">FAJLOVI</th>
                                <th scope="col">OBRIŠI</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in allCases" :key="index">
                                <td>{{ getRowNumber(index) }}</td>
                                <td @dblclick="editField(index, 'vansudski_number')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'vansudski_number'">{{
                                            data.vansudski_number
                                        }}</span>
                                    <input :ref="'input_' + index + '_vansudski_number'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'vansudski_number')"/>
                                </td>
                                <td @dblclick="editField(index, 'mark')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'mark'">{{
                                            data.mark
                                        }}</span>
                                    <input :ref="'input_' + index + '_mark'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'mark')"/>
                                </td>
                                <td @dblclick="editField(index, 'brand')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'brand'">{{
                                            data.brand
                                        }}</span>
                                    <input :ref="'input_' + index + '_brand'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'brand')"/>
                                </td>
                                <td class="first_last_name" @dblclick="editField(index, 'prosecutor')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'prosecutor'">{{
                                            data.prosecutor
                                        }}</span>
                                    <input :ref="'input_' + index + '_prosecutor'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'prosecutor')"/>
                                </td>
                                <td @dblclick="editField(index, 'fail_day')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'fail_day'">{{
                                            data.fail_day
                                        }}</span>
                                    <input :ref="'input_' + index + '_fail_day'" v-else type="date"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'fail_day')"/>
                                </td>
                                <td @dblclick="editField(index, 'date_send_to_expert')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'date_send_to_expert'">{{
                                            data.date_send_to_expert
                                        }}</span>
                                    <input :ref="'input_' + index + '_date_send_to_expert'" v-else type="date"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'date_send_to_expert')"/>
                                </td>
                                <td @dblclick="editField(index, 'date_send_to_mail')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'date_send_to_mail'">{{
                                            data.date_send_to_mail
                                        }}</span>
                                    <input :ref="'input_' + index + '_date_send_to_mail'" v-else type="date"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'date_send_to_mail')"/>
                                </td>
                                <td @dblclick="editField(index, 'date_of_findings')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'date_of_findings'">{{
                                            data.date_of_findings
                                        }}</span>
                                    <input :ref="'input_' + index + '_date_of_findings'" v-else type="date"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'date_of_findings')"/>
                                </td>
                                <td @dblclick="editField(index, 'expert_costs')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'expert_costs'">{{
                                            data.expert_costs
                                        }}</span>
                                    <input :ref="'input_' + index + '_expert_costs'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'expert_costs')"/>
                                </td>
                                <td @dblclick="editField(index, 'date_of_reporting_to_insurance')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'date_of_reporting_to_insurance'">{{
                                            data.date_of_reporting_to_insurance
                                        }}</span>
                                    <input :ref="'input_' + index + '_date_of_reporting_to_insurance'" v-else
                                           type="date" v-model="editableField.value" @blur="cancelEdit()"
                                           @change="saveField(index, 'date_of_reporting_to_insurance')"/>
                                </td>
                                <td>
                                    <span v-if="editableField.index !== index || editableField.field !== 'deadline'">{{
                                            data.deadline
                                        }}</span>
                                    <input v-else type="text" v-model="editableField.value"/>
                                </td>
                                <td @dblclick="editField(index, 'requested_amount')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'requested_amount'">{{
                                            data.requested_amount
                                        }}</span>
                                    <input :ref="'input_' + index + '_requested_amount'" v-else type="text" step="0.01"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'requested_amount')"/>
                                </td>
                                <td @dblclick="editField(index, 'status')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'status'">{{
                                            data.status
                                        }}</span>
                                    <input :ref="'input_' + index + '_status'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'status')"/>
                                </td>
                                <td @dblclick="editField(index, 'mup_note')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'mup_note'">{{
                                            data.mup_note
                                        }}</span>
                                    <input :ref="'input_' + index + '_mup_note'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'mup_note')"/>
                                </td>
                                <td @dblclick="editField(index, 'damage_number')">
                                    <span
                                        v-if="editableField.index !== index || editableField.field !== 'damage_number'">{{
                                            data.damage_number
                                        }}</span>
                                    <input :ref="'input_' + index + '_damage_number'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'damage_number')"/>
                                </td>
                                <td @dblclick="editField(index, 'paid_amount')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'paid_amount'">{{
                                            data.paid_amount
                                        }}</span>
                                    <input :ref="'input_' + index + '_paid_amount'" v-else type="text" step="0.01"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'paid_amount')"/>
                                </td>
                                <td @dblclick="editField(index, 'commission')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'commission'">{{
                                            data.commission
                                        }}</span>
                                    <input :ref="'input_' + index + '_commission'" v-else type="text" step="0.01"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'commission')"/>
                                </td>
                                <td @dblclick="editField(index, 'at')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'at'">{{
                                            data.at
                                        }}</span>
                                    <input :ref="'input_' + index + '_at'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'at')"/>
                                </td>
                                <td @dblclick="editField(index, 'lawsuit')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'lawsuit'">{{
                                            data.lawsuit
                                        }}</span>
                                    <input :ref="'input_' + index + '_lawsuit'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'lawsuit')"/>
                                </td>
                                <td class="not" @dblclick="editField(index, 'note')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'note'">{{
                                            data.note
                                        }}</span>
                                    <input :ref="'input_' + index + '_note'" v-else type="text"
                                           v-model="editableField.value" @blur="cancelEdit()"
                                           @keydown.enter="saveField(index, 'note')"/>
                                </td>
                                <td @click="modalEditFiles(data.id, index)">
                                    <div class="w-100"><i class="fa color-blue fa fa-file" aria-hidden="true"></i></div>
                                </td>
                                <td @click="deleteCase(data.id)">
                                    <div class="w-100" v-if="is_admin == 2"><i class="fa text-danger fa fa-trash"
                                                                               aria-hidden="true"></i>
                                    </div>
                                    <div class="w-100" v-else><i class="fa text-danger fa-solid fa-ban"
                                                                 aria-hidden="true"></i>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="allCases.length < 1" class="bg-light">
                                <td colspan="23" class="text-center">
                                    <vue-simple-spinner></vue-simple-spinner>
                                </td>
                            </tr>
                            <tr v-if="!allCases" class="bg-light">
                                <td colspan="23" class="text-center"><span>NEMA PODATAKA ZA PRIKAZ</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="paginationScroll">
                        <nav aria-label="Page navigation example" class="w-100" v-if="paginateCount > 1">
                            <ul class="pagination">
                                <li v-if="page !== 0" :class="[page == 0 ? 'disabled' : '', 'page-item']"
                                    @click.prevent="getCase(page - 1)">
                                    <a class="page-link one" href="#" tabindex="-1"><i class="fa fa-chevron-left"
                                                                                       aria-hidden="true"></i></a>
                                </li>
                                <li v-for="index in paginateCount" :key="index" @click.prevent="getCase(index - 1)"
                                    :class="[page == index - 1 ? 'active' : '', 'page-item']">
                                    <a class="page-link number" href="#">{{ index }}</a>
                                </li>
                                <li v-if="page !== paginateCount - 1"
                                    :class="[page == paginateCount - 1 ? 'disabled' : '', 'page-item']"
                                    @click.prevent="getCase(page + 1)">
                                    <a class="page-link one" href="#"><i class="fa fa-chevron-right"
                                                                         aria-hidden="true"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from "vue-select";
import EditFailes from "./EditFailes.vue";
import DatePicker from 'vue2-datepicker';
import moment from 'moment';

export default {
    props: ['is_admin'],
    name: "SearchResultVansudski",
    components: {
        'v-select': vSelect,
        'edit-files': EditFailes,
        DatePicker
    },
    data() {
        return {
            allCaseLength: 0,
            allCases: [], // Svi slučajevi
            editableField: {index: null, field: null, value: '', originalValue: ''}, // Držimo trenutno uređeno polje
            search: {
                status: '',
                number_office: '',
                person_1: '',
                mark: '',
                datumSlikanjaOd: '',
                datumSlikanjaDo: '',
                datumSlikanjaOdOriginal: '',
                datumSlikanjaDoOriginal: '',
            },
            maxCount: 0,
            page: 0,
            itemsPerPage: 0,
            paginateCount: 0,
            person_1_list: [],
            person_2_list: [],
            type: 5
        };
    },
    computed: {
        getRowNumber() {
            return (index) => {
                const currentPageStart = (this.maxCount || 0) - this.page * this.itemsPerPage;
                return currentPageStart - index;
            };
        },
    },

    methods: {
        modalEditFiles(data, index) {
            this.$modal.show('edit-files-modal', {'caseID': data, 'caseIndex': index});
        },

        getPersons() {
            axios.post('/case/get/persons', {'case_type_id': this.type}).then(({data}) => {

                this.person_1_list = data.person_1_list;
                this.person_2_list = data.person_2_list;
            }).catch((error) => {
                alert('Došlo je do greške, probajte ponovo ili kontaktirajte administratora')
            })
        },
        deleteCase(caseID) {
            if (this.is_admin == 1) return false;

            this.$confirm({
                message: 'DA LI STE SIGURNI DA ŽELITE DA OBRIŠETE SLUČAJ?',
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
                        axios.delete('/case/delete/case/' + caseID).then(({data}) => {

                            this.getCase(this.page);
                            this.loader = false;

                            this.$confirm({
                                message: 'USPEŠNO BRISANJE',
                                button: {
                                    yes: 'OK'
                                },
                                /**
                                 * Callback Function
                                 * @param {Boolean} confirm
                                 */
                                callback: confirm => {
                                    if (confirm) {

                                    }
                                }
                            })


                        }).catch((error) => {
                            alert('Došlo je do greške, probajte ponovo ili kontaktirajte administratora')
                        })
                    }
                }
            })

        },


        editField(index, field) {
            if (this.is_admin == 2) {
                this.editableField = {
                    index,
                    field,
                    value: this.allCases[index][field],
                    originalValue: this.allCases[index][field], // Čuvamo originalnu vrednost
                };

                this.$nextTick(() => {
                    const inputRef = this.$refs[`input_${index}_${field}`];
                    if (inputRef && typeof inputRef[0].focus === 'function') {
                        inputRef[0].focus(); // Automatski fokus
                        inputRef[0].click(); // Simulira klik na input
                    }
                });
            }

        },

        /**
         * Čuva promene u polju i ažurira na serveru.
         * @param {Number} index - Indeks reda u tabeli.
         * @param {String} field - Naziv kolone.
         */
        saveField(index, field) {
            if (this.editableField.value !== this.allCases[index][field]) {
                this.allCases[index][field] = this.editableField.value;

                // Ažuriraj na serveru
                axios.patch(`/case/edit/vansudski/${this.allCases[index].id}`, {
                    field: field, // Naziv kolone
                    value: this.editableField.value, // Nova vrednost
                }).then((data) => {
                    this.getCase(this.page); // Osveži listu nakon dodavanja
                }).catch(() => {
                    alert('Došlo je do greške, probajte ponovo.');
                    // Ako ažuriranje ne uspe, vraćamo staru vrednost
                    this.allCases[index][field] = this.editableField.originalValue;
                });
            }

            // Resetujemo uređivanje
            this.editableField = {index: null, field: null, value: '', originalValue: ''};
        },

        /**
         * Poništava uređivanje i vraća originalnu vrednost.
         */
        cancelEdit() {
            if (this.editableField.index !== null && this.editableField.field !== null) {
                // Vraćamo originalnu vrednost
                this.allCases[this.editableField.index][this.editableField.field] = this.editableField.originalValue;
            }

            // Resetujemo uređivanje
            this.editableField = {index: null, field: null, value: '', originalValue: ''};
        },

        /**
         * Dohvata slučajeve sa servera.
         * @param {Number} page - Trenutna stranica.
         */
        getCase(page = 0) {
            if(page !== this.page){
                this.allCases = [];
            }
            this.page = page

            if (this.search.datumSlikanjaOd !== "" && this.search.datumSlikanjaOd !== null) {
                this.search.datumSlikanjaOdOriginal = moment(this.search.datumSlikanjaOd).format('DD-MM-YYYY');
            } else {
                this.search.datumSlikanjaOdOriginal = "";
            }
            if (this.search.datumSlikanjaDo !== "" && this.search.datumSlikanjaDo !== null) {
                this.search.datumSlikanjaDoOriginal = moment(this.search.datumSlikanjaDo).format('DD-MM-YYYY');
            } else {
                this.search.datumSlikanjaDoOriginal = "";

            }

            axios.post('/case/get/cases/vansudski', {
                search: this.search,
                page,
            }).then(({data}) => {
                this.allCases = data.data;
                this.maxCount = data.maxCount;
                this.paginateCount = data.count;
                this.itemsPerPage = data.numberData
                this.allCaseLength = this.allCases.length;
                if (this.allCases.length < 1) this.allCases = false;
            }).catch(() => {
                alert('Došlo je do greške, probajte ponovo.');
            });
        },

        /**
         * Resetuje filter za pretragu.
         */
        resetFilter() {
            this.search = {
                status: '',
                number_office: '',
                person_1: '',
                mark: '',
                datumSlikanjaOd: '',
                datumSlikanjaDo: '',
                datumSlikanjaOdOriginal: '',
                datumSlikanjaDoOriginal: '',
            };
        },

        /**
         * Dodaje novi slučaj.
         */
        addNewCase() {
            axios.post('/case/create/case/vansudski', {}).then(({data}) => {
                this.getCase(); // Osveži listu nakon dodavanja
            }).catch(() => {
                alert('Pokušajte ponovo kasnije. Došlo je do greške.');
            });
        },
    },

    /**
     * Automatski se pokreće prilikom kreiranja komponente.
     */
    created() {
        this.getCase();
        this.getPersons()
    },
};
</script>

<style scoped>
@import "vue-select/dist/vue-select.css";
@import 'vue2-datepicker/index.css';

tbody td input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    padding: 0;
}

.table-responsive {
    overflow-x: auto;
}

.table th {
    white-space: nowrap;
    text-align: center;
}

.table th, td {
    min-width: 20px;
    max-width: 300px;
}

.table {
    width: 100%; /* Tabela zauzima punu širinu */
}

input[type="date"]::-webkit-calendar-picker-indicator {
    font-size: 20px; /* Povećavanje */
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.first_last_name {
    min-width: 230px;
    max-width: 1700px;
    white-space: nowrap;
}

.not {
    min-width: 400px;
    max-width: 1700px;
    white-space: pre-wrap;
}

</style>
