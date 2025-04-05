require('./app.js');
window.axios = require('axios');

Vue.component('theme-classes', {
    render: function (createElement) {
        return createElement('style', this.$slots.default)
    }
});

const home = new Vue({
    el: '#home',
    data: {
    	theme: null,
    	themes: themes,
    	index: 0,
    	dialog: {
    		features: false
    	}
    },
    mounted () {

    	this.theme = this.themes[0];

    	setInterval( () => {

    		if( (this.index + 1) < this.themes.length ){
    			this.index++;
    		} else {
    			this.index = 0;
    		}

    		this.theme = this.themes[this.index];

    	}, 3000);

    },
    methods: {



    }
});
