<template>
        <div v-if="profilPictureSmall">
             <div class="container">
                     <div class="row">
                             <div class="col-md-6 offset-md-3" style="text-align:center">
                                     <h3>Update employee:</h3>
                             </div>
                     </div>
                <div class="row">
                        <div class="col-md-6 offset-md-3">
                                <b-form @submit="onSubmit" @reset="onReset" v-if="show">

                                        <b-form-group id="input-group-1" label="User ID:" label-for="input-1">
                                                <input class="form-control" type="text" name = "id" v-model="this.$store.getters.USER.id" id="input-1" readonly>
                                        </b-form-group>

                                        <b-form-group
                                                id="input-group-2"
                                                label="User name:"
                                                label-for="input-2"
                                        >
                                                <b-form-input
                                                        id="input-2"
                                                        v-model="this.$store.getters.USER.username"
                                                        type="name"
                                                        name = "username"
                                                        required
                                                        placeholder="Name"
                                                        ref="name"
                                                ></b-form-input>
                                        </b-form-group>

                                        <b-form-group id="input-group-3" label="Email:" label-for="input-3">
                                                <b-form-input
                                                        id="input-3"
                                                        v-model="this.$store.getters.USER.email"
                                                        type="email"
                                                        name = "email"
                                                        required
                                                        placeholder="Email"
                                                        ref="email"
                                                ></b-form-input>
                                        </b-form-group>

                                        <b-button type="submit" variant="primary">Update</b-button>
                                        <b-button type="reset" variant="danger">Reset</b-button>
<!--                                        <router-link to="/show/" tag="button" class="btn btn-info">Back to Shop</router-link>-->
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
                user: {},
                form: {
                    name: '',
                    email: '',
                    id: '',
                },
                    show: '',
            }
        },


      // setTimeout(func, 1000);
        created() {
                //
                let user = this.$store.getters.USER;
                this.form.name = user.username;
                this.form.email =user.email;
                this.form.id = user.id;

                // this.user = this.$store.getters.USER;
                // this.form.name = this.user.username;
                // this.form.email = this.user.email;
                // this.form.id = this.user.id;
        },
        computed: {

           profilPictureSmall: function() {
                 this.user = this.$store.getters.USER;
                  return this.show= true;
            },

            hasAdditional() {
                return this.additional.length > 0
            }

        },

        methods: {

            onSubmit(evt) {
                evt.preventDefault()
                    // console.log(this.$refs.name.value)
                    // console.log(document.querySelector("input[name=username]").value)
                    // // console.log(evt.getElementsByName("name").innerHTML)
                    // console.log(document.getElementsByName("name")[0].value)
                    // console.log(document.getElementsByName("name").innerHTML)
                    HTTP.post('user/update/', {
                            jwt: localStorage.getItem('token'),
                            id: document.querySelector("input[name=id]").value,
                            username: document.querySelector("input[name=username]").value,
                            email: document.querySelector("input[name=email]").value
                    },{headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(function (response) {
                            if(response.status===200){
                                    alert(response.data.status_message)

                            }
                    }).catch(error => {
                        if(error.response){
                                alert(error.response.data.status_message)
                        }
                    })

                    setTimeout( () => this.$router.push({ path: '/employee'}), 300);
            },
            onReset(evt) {
                evt.preventDefault()
                // Reset our form values
                this.form.id = ''
                this.form.name = ''
                this.form.email = ''

                // Trick to reset/clear native browser form validation state
                this.show = false
                this.$nextTick(() => {
                    this.show = true
                })
            }
        }
    }
</script>