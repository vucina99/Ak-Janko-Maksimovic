<template>
    <div class="options-first options-first-lg">
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
                                        <label for="number_office">BROJ U OSIGURANJU</label>
                                        <input type="text" name="number_court" id="number_court"
                                               v-model="search.institution_number" class="form-control"
                                               placeholder="BROJ U OSIGURANJU">
                                    </div>
                                </div>
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
                                        <label for="number_office">ŠTETNIK</label>
                                        <v-select :options="person_2_list" :id="'defendants'" label="defendants"
                                                  v-model="search.person_2" placeholder="ŠTETNIK"></v-select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-8-col">
                                    <div class="form-group search-font-size">
                                        <label for="archive_number">REGISTASKE OZNAKE</label>
                                        <input type="text" name="mark" id="archive_number" v-model="search.mark"
                                               class="form-control" placeholder="REGISTASKE OZNAKE">
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
                    <button class="btn btn-success px-5" @click="addNewCase()">DODAJ <i class="fa fa-plus"
                                                                                        aria-hidden="true"></i></button>
                    <div class="table-responsive">
                        <table class="table table-hover table-text-size table-cursor">
                            <thead class="bg-blue text-personal-light">
                            <tr>
                                <th scope="col">BR. KANC</th>
                                <th scope="col">REG OZNAKE</th>
                                <th scope="col">MARKA I MODEL</th>
                                <th scope="col">IME I PREZIME</th>
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
                                <th scope="col">NAPOMENA</th>
                                <th scope="col">FAJLOVI</th>
                                <th scope="col">OBRIŠI</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in allCases" :key="index">
                                <td @dblclick="editField(index, 'vansudski_number')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'vansudski_number'">{{ data.vansudski_number }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'vansudski_number')" />
                                </td>
                                <td @dblclick="editField(index, 'mark')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'mark'">{{ data.mark }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'mark')" />
                                </td>
                                <td @dblclick ="editField(index, 'brand')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'brand'">{{ data.brand }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'brand')" />
                                </td>
                                <td @dblclick="editField(index, 'prosecutor')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'prosecutor'">{{ data.prosecutor }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'prosecutor')" />
                                </td>
                                <td @dblclick="editField(index, 'fail_day')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'fail_day'">{{ data.fail_day }}</span>
                                    <input v-else type="date" placeholder="dd-mm-yyyy" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'fail_day')" />
                                </td>
                                <td @dblclick="editField(index, 'date_send_to_expert')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'date_send_to_expert'">{{ data.date_send_to_expert }}</span>
                                    <input v-else type="date" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'date_send_to_expert')" />
                                </td>
                                <td @dblclick="editField(index, 'date_send_to_mail')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'date_send_to_mail'">{{ data.date_send_to_mail }}</span>
                                    <input v-else type="date" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'date_send_to_mail')" />
                                </td>
                                <td @dblclick="editField(index, 'date_of_findings')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'date_of_findings'">{{ data.date_of_findings }}</span>
                                    <input v-else type="date" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'date_of_findings')" />
                                </td>
                                <td @dblclick="editField(index, 'expert_costs')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'expert_costs'">{{ data.expert_costs }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'expert_costs')" />
                                </td>
                                <td @dblclick="editField(index, 'date_of_reporting_to_insurance')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'date_of_reporting_to_insurance'">{{ data.date_of_reporting_to_insurance }}</span>
                                    <input v-else type="date" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'date_of_reporting_to_insurance')" />
                                </td>
                                <td @dblclick="editField(index, 'deadline')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'deadline'">{{ data.deadline }}</span>
                                    <input v-else type="date" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'deadline')" />
                                </td>
                                <td @dblclick="editField(index, 'requested_amount')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'requested_amount'">{{ data.requested_amount }}</span>
                                    <input v-else type="text" step="0.01" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'requested_amount')" />
                                </td>
                                <td @dblclick="editField(index, 'status')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'status'">{{ data.status }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'status')" />
                                </td>
                                <td @dblclick="editField(index, 'mup_note')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'mup_note'">{{ data.mup_note }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'mup_note')" />
                                </td>
                                <td @dblclick="editField(index, 'damage_number')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'damage_number'">{{ data.damage_number }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'damage_number')" />
                                </td>
                                <td @dblclick="editField(index, 'paid_amount')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'paid_amount'">{{ data.paid_amount }}</span>
                                    <input v-else type="text" step="0.01" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'paid_amount')" />
                                </td>
                                <td @dblclick="editField(index, 'commission')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'commission'">{{ data.commission }}</span>
                                    <input v-else type="text" step="0.01" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'commission')" />
                                </td>
                                <td @dblclick="editField(index, 'at')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'at'">{{ data.at }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'at')" />
                                </td>
                                <td @dblclick="editField(index, 'lawsuit')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'lawsuit'">{{ data.lawsuit }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'lawsuit')" />
                                </td>
                                <td @dblclick="editField(index, 'note')">
                                    <span v-if="editableField.index !== index || editableField.field !== 'note'">{{ data.note }}</span>
                                    <input v-else type="text" v-model="editableField.value" @blur="cancelEdit()" @keydown.enter="saveField(index, 'note')" />
                                </td>
                                <td>
                                    <div class="w-100"><i class="fa color-blue fa fa-file" aria-hidden="true"></i></div>
                                </td>
                                <td>
                                    <div class="w-100"><i class="fa text-danger fa fa-trash" aria-hidden="true"></i></div>
                                </td>
                            </tr>
                            <tr v-if="allCases.length < 1" class="bg-light">
                                <td colspan="22" class="text-center">
                                    <vue-simple-spinner></vue-simple-spinner>
                                </td>
                            </tr>
                            <tr v-if="!allCases" class="bg-light">
                                <td colspan="22" class="text-center"><span>NEMA PODATAKA ZA PRIKAZ</span></td>
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

export default {
    name: "SearchResultVansudski",
    components: {
        'v-select': vSelect,
    },
    data() {
        return {
            allCases: [], // Svi slučajevi
            editableField: { index: null, field: null, value: '', originalValue: '' }, // Držimo trenutno uređeno polje
            search: {
                institution_number: '',
                number_office: '',
                person_1: '',
                person_2: '',
                mark: '',
            },
            page: 0,
            paginateCount: 0,
            person_1_list: [],
            person_2_list: [],
        };
    },
    methods: {
        /**
         * Pokreće uređivanje polja.
         * @param {Number} index - Indeks reda u tabeli.
         * @param {String} field - Naziv kolone.
         */
        editField(index, field) {
            this.editableField = {
                index,
                field,
                value: this.allCases[index][field],
                originalValue: this.allCases[index][field], // Čuvamo originalnu vrednost
            };
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
                }).then((data) =>{
                    console.log(this.page)
                    this.getCase(this.page); // Osveži listu nakon dodavanja
                }).catch(() => {
                    alert('Došlo je do greške, probajte ponovo.');
                    // Ako ažuriranje ne uspe, vraćamo staru vrednost
                    this.allCases[index][field] = this.editableField.originalValue;
                });
            }

            // Resetujemo uređivanje
            this.editableField = { index: null, field: null, value: '', originalValue: '' };
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
            this.editableField = { index: null, field: null, value: '', originalValue: '' };
        },

        /**
         * Dohvata slučajeve sa servera.
         * @param {Number} page - Trenutna stranica.
         */
        getCase(page = 0) {
            this.allCases = [];
            axios.post('/case/get/cases/vansudski', {
                search: this.search,
                page,
            }).then(({ data }) => {
                this.allCases = data.data;
                this.paginateCount = data.count;
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
                institution_number: '',
                number_office: '',
                person_1: '',
                person_2: '',
                mark: '',
            };
        },

        /**
         * Dodaje novi slučaj.
         */
        addNewCase() {
            axios.post('/case/create/case/vansudski', {}).then(({ data }) => {
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
    },
};
</script>

<style scoped>
@import "vue-select/dist/vue-select.css";

tbody td input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    padding: 0;
}
.table-responsive {
    overflow-x: auto; /* Horizontalni skrol ako je širina veća od dostupnog prostora */
}

.table th{
    white-space: nowrap; /* Sprečava prelazak teksta u novi red */
    text-align: center; /* Centriraj tekst u kolonama */
}

.table th, td {
    min-width: 20px; /* Minimalna širina za th elemente */
    max-width: 300px; /* Minimalna širina za th elemente */
}

.table {
    table-layout: auto; /* Automatska raspodela širine kolona */
    width: 100%; /* Tabela zauzima punu širinu */
}
input[type="date"]::-webkit-calendar-picker-indicator {
    font-size: 20px; /* Povećavanje */
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
