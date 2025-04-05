require('./app.js');
window.axios = require('axios');
import { CountUp } from 'countup.js';

Vue.component('load-image', require('./components/LoadImage').default);

var themeCount,
	searchTimeout;

const browse = new Vue({
    el: '#browse',
    data: {
    	loading: true,
    	themes: {},
    	products: [],
    	page: 1,
    	search: '',
    	sort: 'newest',
    	brightness: 'all',
    	color: 'all'
    },
    watch: {

    	sort (now, prev){
            this.page = 1;
    		this.getThemes(this.page);
    	},

    	brightness (now, prev){
            this.page = 1;
    		this.getThemes(this.page);
    	},

    	color (now, prev){
            this.page = 1;
    		this.getThemes(this.page);
    	},

    	search (now, prev){

    		if( searchTimeout ){
    			clearTimeout( searchTimeout );
    		}

    		searchTimeout = setTimeout( () => {
                this.page = 1;
    			this.getThemes(this.page);
    		}, 1000);

    	}

    },
    mounted () {

    	const locationString = window.location.href.split('/');

    	// Brightness
    	if( locationString[4] && ['all','dark','medium','light'].indexOf(locationString[4]) > -1 ){
    		this.brightness = locationString[4];
    	}

    	// Color
    	if( locationString[5] && ['all','red','orange','yellow','green','blue','purple'].indexOf(locationString[5]) > -1 ){
    		this.color = locationString[5];
    	}

    	// Sort
    	if( locationString[6] && ['featured','newest','oldest','most-downloaded','least-downloaded','highest-rated','lowest-rated'].indexOf(locationString[6]) > -1 ){
    		this.sort = locationString[6];
    	}

    	// Page
    	if( locationString[7] && parseInt(locationString[7]) ){
    		this.page = parseInt(locationString[7]);
    	}

    	// Theme Count
    	themeCount = new CountUp('themecount', 0, {
    		duration: 0.5
    	});

    	// No Parameters
    	this.getThemes( this.page );

    },
    methods: {

    	getThemes (page) {

    		this.loading = true;
    		this.page = page;

            axios.post('/browse', {
            	page: this.page,
            	search: this.search,
                sort: this.sort,
                brightness: this.brightness,
                color: this.color
            })
            .then( (response) => {

            	this.loading = false;
            	this.themes = response.data.themes;
            	this.products = response.data.products;
            	themeCount.update( response.data.themes.total );

            	this.updateUrl();

                document.title = response.data.themes.total + ' Free ' + document.title;

            })
            .catch( (error) => {


            });

    	},

    	updateUrl () {

    		const params = ['browse', this.brightness, this.color, this.sort, this.page];

            var title = [];

            if( this.brightness && this.brightness != 'all' ){
                title.push(this.brightness.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                }));
            }

            if( this.color && this.color != 'all' ){
                title.push(this.color.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                }));
            }

            document.title = title.join(' ') + ' Ableton 10 Themes';

            history.pushState({}, null, '/' + params.join('/'));

    	},


    },

    computed: {

    	startPage() {
			// When on the first page
			if (this.themes.current_page === 1) {
				return 1;
			}
			// When on the last page
			if (this.themes.current_page === this.themes.last_page) {
				return this.themes.last_page - 5;
			}
			// When in between
			return this.themes.current_page - 3;
		},

    	paginationNumbers () {

    		const range = [];

			for (let i = this.startPage;
				i <= Math.min(this.startPage + 6, this.themes.last_page);
				i+= 1 ) {

				if(i > 0){

					range.push({
						name: i,
						current: i === this.themes.current_page
					});

				}

			}

			return range;

    	}

    }
});
