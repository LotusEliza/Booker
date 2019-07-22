    <template>
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3" style="text-align:center">
                        <h3>Employee list:</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-3" style="text-align:center">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th scope="row">Id</th>
                                <th scope="row">Name</th>
                                <th scope="row">Email</th>
                            </tr>
                            <tr v-for="item in employee">
                                <td scope="row">{{item.id}}</td>
                                <td scope="row">{{item.username}}</td>
                                <td scope="row">
                                    <a :href="`mailto:${item.email}`">{{item.email}}</a>
                                </td>
                                <td scope="row">
                                    <button v-on:click="removeUser(item)" class="btn btn-secondary btn-sm">
                                        Remove
                                    </button>
                                </td>
                                <td scope="row">
                                    <router-link v-on:click.native="editUser(item)" to="/edit/" tag="button"
                                     class="btn btn-secondary btn-sm">
                                        Edit
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <modalRegister></modalRegister>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>

    import {HTTP} from "../main";
    import modalRegister from "./modalRegister";

    export default {
        components: {modalRegister},
        data () {
            return {
                employee: [],
                id_role: '',
            }
        },

        created() {
            this.id_role = this.$store.getters.ID_ROLE;
            this.getUsers();
        },

        methods: {

            getUsers(){
                HTTP.post('user/show/', {
                        jwt: localStorage.getItem('token')
                    },
                    {headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        }})
                    .then(res => {
                        console.log(res.data);
                        this.employee = res.data.data.users
                    }).catch(error => {
                    console.log('error', error);
                })
            },

            removeUser(user){
                if (confirm('Are you sure you want to delete this user: '+user.username+'?')){
                    HTTP.post('user/delete/', {
                        jwt: localStorage.getItem('token'),
                        id: user.id
                    },{headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        }})
                        .then(response => {
                            if(response.status===200){
                                alert(response.data.status_message)
                            }
                        }).catch(error => {
                        if(error.response){
                            alert(error.response.data.status_message)

                        }
                    })
                }
                setTimeout( () => this.getUsers(), 300);

            },

            editUser(user){
                this.$store.commit('SET_USER', user);
            }
        }

    }
</script>

<style>
</style>