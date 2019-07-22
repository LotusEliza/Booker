<template>
    <nav>
        <ul>
            <li><router-link to="/" exact>Home</router-link></li>
            <li><router-link to="/calendar" exact>Calendar</router-link></li>
            <li><router-link to="/book" exact>Book It</router-link></li>
            <li><router-link v-if="this.$store.getters.ID_ROLE == 2 && isLoggedIn" to="/employee/" exact>Employee List</router-link></li>
            <span v-if="isLoggedIn" ><a @click="logout">Logout</a></span>
        </ul>
    </nav>
</template>

<script>

    export default {

        data() {
            return {
                id_role: this.$store.getters.ID_ROLE,
            }
        },

        created (){
        },

        computed : {
            isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
        },

        methods: {
            logout: function () {
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push('/')
                    })
            }
        },
    }
</script>

<style scoped>
    ul{
        list-style-type: none;
        text-align: center;
        margin: 0;
    }
    li{
        display: inline-block;
        margin: 0 10px;
    }
    a{
        color: #fff;
        text-decoration: none;
        padding: 6px 8px;
        border-radius: 10px;
    }
    nav{
        background: #444;
        padding: 14px 0;
        margin-bottom: 40px;
    }
    .router-link-active{
        background: #eee;
        color: #444;
    }

    span{
        color: white;
    }
</style>
