<template>
    <div>
        <b-button v-b-modal.modal-register>Register</b-button>
            <b-modal
                    id="modal-register"
                    ref="modal"
                    title="Please Register"
                    @show="resetModal"
                    @hidden="resetModal"
                    @ok="handleOk">
                <form ref="form" @submit.stop.prevent="register">
                    <b-form-group
                            :state="nameState"
                            label="Name"
                            label-for="name-input"
                            invalid-feedback="Name is required">
                        <b-form-input
                                id="name-input"
                                v-model="name"
                                :state="nameState"
                                required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            :state="passwordState"
                            label="Password"
                            label-for="password-input"
                            invalid-feedback="Password is required">
                        <b-form-input
                                id="password-input"
                                v-model="password"
                                :state="passwordState"
                                required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            :state="emailState"
                            label="Email"
                            label-for="email-input"
                            invalid-feedback="Email is required">
                        <b-form-input
                                id="email-input"
                                v-model="email"
                                :state="emailState"
                                required
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>
    </div>
</template>

<script>

    import {HTTP} from "../main";

    export default {
        name: 'ModalRegister',

        data() {
            return {
                name: '',
                email: '',
                jwt: null,
                nameState: null,
                emailState: null,
                password: '',
                passwordState: null,
                submittedNames: []
            }
        },

        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.emailState = valid ? 'valid' : 'invalid'
                this.nameState = valid ? 'valid' : 'invalid'
                this.passwordState = valid ? 'valid' : 'invalid'
                return valid
            },

            resetModal() {
                this.name = ''
                this.nameState = null
                this.password = ''
                this.passwordState = null
                this.email = ''
                this.emailState = null
            },

            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault()
                // Trigger submit handler
                this.register()
            },

            register() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }

                HTTP.post('user/create/', {
                    id_role: '1',
                    username: this.name,
                    password: this.password,
                    email: this.email,
                },{headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                }})
                .then(function (response) {
                    if(response.status===201){
                        alert(response.data.status_message)
                    }
                })
                .catch(function (error) {
                    if(error.response){
                       alert(error.response.data.status_message)
                    }
                });

                this.$nextTick(() => {
                    this.$refs.modal.hide()
                })
            }
        }
    }
</script>
<style scoped>
    * {
        color: #50B9C5;
        font-weight: bold;
        font-size: large;
    }
</style>