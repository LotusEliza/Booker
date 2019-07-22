<template>
  <div class="container">
    <div class="row" v-if="!isLoggedIn">
      <div class="col-md-6 offset-md-3" style="text-align:center">
        <h2>WELCOME TO OUR BOOKING SYSTEM!</h2><br><br>
        <h3>Please register:</h3>
        <modalRegister></modalRegister>
        <h3>or login:</h3>
        <modalLogin></modalLogin>
      </div>
    </div>
    <div class="row" v-else-if="isLoggedIn">
      <div class="col-md-6 offset-md-3" style="text-align:center">
        <h1>WELCOME TO OUR BOOKING SYSTEM OUR DEAR USER!!!!!</h1><br><br>
      </div>
    </div>
  </div>
</template>

<script>
    import modalRegister from "./modalRegister";
    import modalLogin from "./modalLogin";

    export default {
      components: {modalRegister, modalLogin},
      name: 'HelloWorld',

      data () {
        return {
        }
      },

        computed : {
            isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
        },
    }
</script>