require('../app.js');

const advertCreate = new Vue({
    el: '#advert-create',
    methods: {

    	appendUrl (text){

    		var elem = document.getElementById('url');
		    var old  = elem.value;
		    elem.value = old + text;

    	}

    }
});
