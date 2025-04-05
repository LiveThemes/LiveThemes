require('./app.js');
window.axios = require('axios');

const theme = new Vue({
    el: '#theme',
    data: {
        loading: false,
        user: window.user,
        theme: window.theme,
        comments: [],
        rateStars: 0,
        rated: false,
        dialog: {
            share: false,
            instructions: false,
            error: false,
            delete: false,
            download: false
        },
        linkCopied: false,
        showFullPreview: false,
        comment: '',
        download: {
            title: 'Preparing your download',
            count: 0,
            percent: 0,
            timer: null
        }
    },
    mounted (){

        this.loadComments();

    },
    methods: {

        downloadTheme () {

            this.dialog.download = true;

            if( this.download.timer ){
                clearInterval(this.download.timer);
            }

            this.download.count = 0;
            this.download.percent = 0;

            this.download.timer = setInterval( () => {
                if(this.download.count < 5){
                    this.download.count++;
                    this.download.percent = (this.download.count/5) * 100;
                }
            }, 1000);

            setTimeout( () => {
                this.$refs.download.submit();
                clearInterval(this.download.timer);
                this.download.percent = 100;
            }, 5000);

        },

        loadComments () {

            axios.get(this.theme.url + '/comments')
                .then( (response) => {
                    this.comments = response.data
                });

        },

        deleteComment () {

            this.loading = true;

            axios.post(this.theme.url + '/comment/delete', {
                id: this.dialog.delete
            })
            .then( (response) => {

                this.loading = false;

                this.dialog.delete = false;

                this.loadComments();

            })
            .catch( (error) => {

                this.loading = false;

            });

        },

        rateTheme (stars) {

            this.loading = true;

            axios.post(this.theme.url + '/rate', {
                id: this.theme.id,
                stars: stars
            })
            .then( (response) => {

                this.loading = false;

                this.theme.average_rating = parseFloat(response.data.average_rating);

                this.rated = true;

                setTimeout(() => {
                    this.rated = false;
                }, 3000);

            })
            .catch( (error) => {

                this.loading = false;

            });

        },

        addComment () {

            this.loading = true;

            axios.post(this.theme.url + '/comment/store', {
                id: this.theme.id,
                comment: this.comment
            })
            .then( (response) => {

                this.loading = false;
                this.comment = '';

                this.loadComments();

            })
            .catch( (error) => {

                this.loading = false;

                if(error.response && error.response.data.message){
                    this.dialog.error = error.response.data.message;
                }

            });

        },

        copyLink (link) {

            var linkInput = document.getElementById("theme-link");

            linkInput.select();
            linkInput.setSelectionRange(0, 99999);

            document.execCommand("copy");

            this.linkCopied = true;

        },

        shareTwitter () {

            var url = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(this.theme.url) + '&text=' + encodeURIComponent(this.theme.name + ' Ableton Theme');

            window.open(url);

        },

        shareFacebook () {

            var url = 'https://www.facebook.com/dialog/share?app_id=503342856736879&href=' + encodeURIComponent(this.theme.url);

            window.open(url);

        },

        shareReddit () {

            var url = 'https://www.reddit.com/submit?url=' + encodeURIComponent(this.theme.url) + '&title=' + encodeURIComponent(this.theme.name + ' Ableton Theme');

            window.open(url);

        }

    }
});
