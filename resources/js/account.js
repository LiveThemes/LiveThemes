require('./app.js');

const home = new Vue({
    el: '#account',
    methods: {

        deleteAccount (){

            if( !confirm('Are you absolutely SURE you want to delete your account?') ){
                return false;
            }

            this.$refs.deleteAccount.submit();

        }

    }
});
