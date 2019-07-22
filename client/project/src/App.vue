<template>
    <div>
        <app-header></app-header>
        <router-view></router-view>
        <app-footer></app-footer>
    </div>
</template>

<script>

    import header from './components/header.vue';
    import footer from './components/footer.vue';
    import editEmployee from './components/editEmployee.vue';
    import modalRegister from './components/modalRegister.vue';
    import modalLogin from './components/modalLogin.vue';
    import employee from './components/employee.vue';

export default {
  name: 'App',
  base: '/booker/server/',
    components:{
      'employee': employee,
      'app-header': header,
      'app-footer': footer,
      'modal': modalRegister,
      'modalLogin': modalLogin,
      'editEmployee': editEmployee,
    },

    data () {
        return {
        }
    },
    methods: {
    }
}
</script>

<style>
</style>


