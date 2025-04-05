require('./app.js');
window.axios = require('axios');

Vue.component('load-image', require('./components/LoadImage').default);

var themeCount,
	searchTimeout;

const charts = new Vue({
    el: '#charts',
    data: {
        init: false,
    	loading: true,
        time: 'week',
        mostDownloaded: [],
        highestRated: [],
        products1: [],
        products2: []
    },
    mounted () {

        this.getThemes();

    },
    watch: {

        time (now, prev){

            this.getThemes();

        }

    },
    methods: {

    	getThemes (page) {

    		this.loading = true;

            this.mostDownloaded = [];
            this.highestRated = [];

            axios.post('/charts', {
            	time: this.time
            })
            .then( (response) => {

                this.init = true;

                this.loading = false;

                this.mostDownloaded = response.data.mostDownloaded;

                this.highestRated = response.data.highestRated;

                this.products1 = response.data.products1;

                this.products2 = response.data.products2;

            	console.log(response);
            })
            .catch( (error) => {

                this.loading = false;

                console.log(error);

            });

    	},


    }
});
