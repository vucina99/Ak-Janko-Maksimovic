<template>
    <div class="options-first options-first-lg  ">
        <div class="container">
            <div class="row">
                <div class="col-12 pb-2 pt-2 bg-light">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group search-font-size">
                                <label for="client1">STRANKA 1</label>
                                <input type="text" name="client1" id="client1"
                                       class="form-control"   placeholder="STRANKA 1" v-model="searchData.person_1">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group search-font-size">
                                <label for="number_office">BROJ U KANCELARIJI</label>
                                <input type="text" name="number_court" id="number_office"
                                       class="form-control" v-model="searchData.number_office"
                                       placeholder="BROJ U KANCELARIJI">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group search-font-size">
                                <label for="pretraga" class="text-light">dugme za pretragu</label>
                                <button class="btn btn-primary w-100 mr-1 pb-6"  style="height:32.20px; padding-bottom:2.5px;  padding-top:1.5px;"  @click.prevent="getTrialDates()">
                                    PRETRAGA <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12"><br><br>
                    <FullCalendar  :options='calendarOptions'/>

                </div>
            </div>
        </div>




        <modal name="show-cases-search" :shiftY="0.1" :scrollable="true" :adaptive="true" :resizable="true"   height="auto">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-4  d-flex justify-content-end w-100 ">
                        <div class="text-right w-25 " @click="closeModal()">
                            <i class="fa fa-times fa-2x text-muted" aria-hidden="true"></i>
                        </div>
                    </div>

                    <div class="col-12 pt-4">
                        <div class="table-responsive">

                            <table class="table  table-hover table-text-size table-cursor">
                                <thead class="bg-blue text-personal-light">
                                <tr>
                                    <th>STRANKA 1</th>
                                    <th>STRANKA 2</th>
                                    <th>DATUM</th>
                                    <th>VREME</th>
                                    <th>BROJ U KANCELARIJI</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr v-if="showSearchedData.length > 0" v-for="(trial, index) in showSearchedData">
                                    <td >{{ trial.prosecutor }}</td>
                                    <td >{{ trial.defendants }}</td>
                                    <td >{{ trial.dateParse }}</td>
                                    <td >
                                        {{ trial.time ? trial.time.slice(0, 5) : '' }}
                                    </td>
                                    <td >{{ trial.numberOffice }}</td>

                                </tr>

                                <tr v-if="showSearchedData.length == 0" class="bg-light">

                                    <td colspan="5"
                                        class="text-center">
                                        <span>NEMA PODATAKA ZA PRIKAZ</span></td>

                                </tr>

                                </tbody>
                            </table>
                            <br>

                        </div>
                    </div>
                </div>
            </div>
            <br>
        </modal>



    </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import srLocale from "@fullcalendar/core/locales/sr";

export default {
    name: "Calendar",
    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            searchData:{
              'number_office' : '',
              'person_1' : ''
            },
            showSearchedData : [],
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                weekends: false,
                events: [],
                dateClick: this.handleDateClick,
                locale: 'sr-ME',
                themeSystem: bootstrap,
                buttonText: {
                    today: "DANAS"
                }
            }
        }
    },
    methods: {
        handleDateClick: function (arg) {
            window.location.href = "/all-trial-from/" + arg.dateStr;

        },
        getTrialDates(){
            this.showSearchedData = [];
            if(this.searchData.number_office  == "" && this.searchData.person_1  == "" ){
                return;
            }
            axios.post("/trial/get-trial-search" , this.searchData).then(({data}) => {
                this.showSearchedData = data.data;
                this.$modal.show('show-cases-search');
            })
        },
        closeModal() {
            this.$modal.hide('show-cases-search');
        },
    }
}
</script>

<style scoped>
/*@import 'fullcalendar/dist/fullcalendar.css';*/

</style>
