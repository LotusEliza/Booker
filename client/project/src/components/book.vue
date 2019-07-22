<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3" style="text-align:center">
                    <h1>Please book it:</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">

                    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

                        <b-form-group id="input-group-1" label="Select day:" label-for="input-1">
                            <b-form-input
                                    id="input-1"
                                    v-model="form.day"
                                    required
                                    placeholder="2019-07-22"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-2" label="Select time start:" label-for="input-2">
                            <b-form-input
                                    id="input-2"
                                    v-model="form.timeStartV"
                                    required
                                    placeholder="08:00:00"
                            ></b-form-input>
                        </b-form-group>


                        <b-form-group id="input-group-3" label="Select time end:" label-for="input-3">
                            <b-form-input
                                    id="input-3"
                                    v-model="form.timeEndV"
                                    required
                                    placeholder="09:00:00"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-4" label="Note:" label-for="input-4">
                            <b-form-input
                                    id="input-4"
                                    v-model="form.note"
                                    required
                                    placeholder="Note"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group
                                id="input-group-5"
                                label="Room:"
                                label-for="input-5">
                            <b-form-select
                                    id="input-5"
                                    v-model="form.room"
                                    :options="rooms"
                                    required
                            ></b-form-select>
                        </b-form-group>

                        <b-form-group id="input-group-6" >
                            <b-form-checkbox-group v-model="form.recurrent" id="checkboxes-6" >
                                <b-form-checkbox value="yes" v-model="additional" :disabled="hasAdditional" >recurrent</b-form-checkbox>
                                <b-form-checkbox value="no" v-model="additional" :disabled="hasAdditional" >not recurrent</b-form-checkbox>
                            </b-form-checkbox-group>
                        </b-form-group>

                        <div v-if="additional[0] === 'yes'">
                            <b-form-group
                                    id="input-group-7"
                                    label="frequency:"
                                    label-for="input-7">
                                <b-form-select
                                        id="input-7"
                                        v-model="form.frequency"
                                        :options="frequency"
                                        required
                                ></b-form-select>
                            </b-form-group>

                            <b-form-group id="input-group-8" label="duration:" label-for="input-8">
                                <b-form-input
                                        type="number"
                                        min="1.00"
                                        max="4.00"
                                        v-model="form.duration"
                                        placeholder="duration"
                                        required
                                ></b-form-input>
                            </b-form-group>
                        </div>

                        <b-button type="submit" variant="primary">Submit</b-button>
                        <b-button type="reset" variant="danger">Reset</b-button>
                        <router-link to="/calendar/" tag="button" class="btn btn-info">Back to Calendar</router-link>
                    </b-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HTTP} from "../main";

    export default {

        data() {
            return {
                id_user: this.$store.getters.ID_USER,
                rooms: ['1', '2', '3'],
                frequency: ['weekly', 'bi-weekly', 'monthly'],
                form: {
                    day: '',
                    timeStartV: '',
                    timeEndV: '',
                    note: '',
                    room: '',
                    duration: '',
                    frequency: '',
                    recurrent: '',
                },
                show: true,
                additional: 0
            }
        },

        created(){
        },

        computed:{
            hasAdditional() {
                return this.additional.length > 0
            }
        },

        methods: {

            createRecurEvent(){
                HTTP.post('event/create_rec/', {
                    jwt: localStorage.getItem('token'),
                    id_user: this.id_user,
                    id_room: this.form.room,
                    description: this.form.note,
                    time_start: this.form.day + ' ' + this.form.timeStartV,
                    time_end: this.form.day + ' ' + this.form.timeEndV,
                    recur: this.form.frequency,
                    duration: this.form.duration
                },{headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(function (response) {
                        if(response.status===200){
                            alert(response.data.status_message)
                        }
                    })
                    .catch(function (error) {
                        if(error.response){
                            alert(error.response.data.status_message)
                        }
                        console.log('error', error);
                    });

                setTimeout( () => this.$router.push({ path: '/calendar'}), 500);
            },

            createEvent(){
                HTTP.post('event/create/', {
                    jwt: localStorage.getItem('token'),
                    id_user: this.id_user,
                    id_room: this.form.room,
                    description: this.form.note,
                    time_start: this.form.day + ' ' + this.form.timeStartV,
                    time_end: this.form.day + ' ' + this.form.timeEndV,
                },{headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(function (response) {
                        if(response.status===200){
                            alert(response.data.status_message)
                        }
                    })
                    .catch(function (error) {
                        if(error.response){
                            alert(error.response.data.status_message)
                        }
                        console.log('error', error);
                    });

                setTimeout( () => this.$router.push({ path: '/calendar'}), 500);
            },

            onSubmit(evt) {
                evt.preventDefault()
                if(this.additional[0] === 'no'){
                    this.createEvent()
                }else if(this.additional[0] === 'yes'){
                    this.createRecurEvent()
                }else{
                    alert("Please select recurrent or not!")
                }

            },

            onReset(evt) {
                evt.preventDefault()
                // Reset our form values
                this.form.day = ''
                this.form.recurrent = ''
                this.form.timeStartV = ''
                this.form.timeEndV = ''
                this.form.note = ''
                this.form.room = ''
                this.additional = ''

                // Trick to reset/clear native browser form validation state
                this.show = false
                this.$nextTick(() => {
                    this.show = true
                })
            }
        }

    }
</script>