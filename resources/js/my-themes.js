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
        saving: false,
    	themes: {},
    	products: [],
    	page: 1,
    	search: '',
    	sort: 'newest',
    	visibility: 'all',
        deleteId: null
    },
    watch: {

    	sort (now, prev){
    		this.getThemes(this.page);
    	},

    	visibility (now, prev){
    		this.getThemes(this.page);
    	},

    	search (now, prev){

    		if( searchTimeout ){
    			clearTimeout( searchTimeout );
    		}

    		searchTimeout = setTimeout( () => {
    			this.getThemes(this.page);
    		}, 1000);

    	}

    },
    mounted () {

    	const locationString = window.location.href.split('/');

    	// Visibility
    	if( locationString[4] && ['all','public','private'].indexOf(locationString[4]) > -1 ){
    		this.visibility = locationString[4];
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

            axios.post('/my-themes', {
            	page: this.page,
            	search: this.search,
                sort: this.sort,
                visibility: this.visibility
            })
            .then( (response) => {

            	this.loading = false;
            	this.themes = response.data.themes;
            	this.products = response.data.products;

            	this.updateUrl();

            })
            .catch( (error) => {


            });

    	},

    	updateUrl () {

    		const params = ['my-themes', this.visibility, this.sort, this.page];

            var title = [];

            if( this.visibility && this.visibility != 'all' ){
                title.push(this.visibility.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                }));
            }

            document.title = title.join(' ') + ' Ableton 10 Themes';

            history.pushState({}, null, '/' + params.join('/'));

    	},

        toggleVisibility (theme_id) {

            axios.post('/theme/' + theme_id + '/visibility', {
                theme_id: theme_id
            })
            .then( (response) => {
                this.getThemes( this.page );

            });

        },

        deleteTheme () {

            this.saving = true;

            axios.post('/theme/' + this.deleteId + '/delete', {
                theme_id: this.deleteId
            })
            .then( (response) => {

                this.saving = false;
                this.deleteId = null;
                this.getThemes( this.page );

            })
            .catch( (error) => {

                this.saving = false;
                this.deleteId = null;

                alert('There was an error deleting your theme!');

            });

        }


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
