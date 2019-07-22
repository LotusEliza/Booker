<template>
    <div>
        <b-button v-b-modal.modal-login>Login</b-button>
        <b-modal
                id="modal-login"
                ref="modal"
                title="Submit Your Name"
                @show="resetModal"
                @hidden="resetModal"
                @ok="handleOk">
            <form ref="form" @submit.stop.prevent="login">
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
        name: 'ModalLogin ',

        data() {
            return {
                name: '',
                nameState: null,
                password: '',
                passwordState: null,
                email: '',
                emailState: null,
                submittedNames: []
            }
        },

        methods: {

            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
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
                this.login()
            },

            login() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }

                let token = '';
                let id_role = '';
                let id_user = '';

                HTTP.post('user/login/', {
                    password: this.password,
                    username: this.name,
                    email: this.email,
                }, {headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                .then(function (response) {
                    token = response.data.data.jwt
                    id_role = response.data.data.id_role
                    id_user = response.data.data.id
                })
                .catch(function (error) {
                    if(error.response){
                        alert(error.response.data.status_message)
                    }
                    localStorage.removeItem('token')
                });

                setTimeout(()=>this.$store.commit('SET_ID_ROLE', id_role), 500);
                setTimeout(()=>this.$store.commit('SET_ID_USER', id_user), 500);

                setTimeout(() => this.$store.dispatch('login', {
                    name: this.name,
                    password: this.password,
                    token: token,
                })
                    .then(() => this.$router.push('/'))
                    .catch(err => console.log(err)), 500);

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