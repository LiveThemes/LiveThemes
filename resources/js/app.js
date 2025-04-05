require('./bootstrap');

import Helpers from './helpers.js';

window.Vue = require('vue');

// Mobile Check
if( Helpers.mobileCheck() ){
    var body = document.body;
    body.classList.add("mobile");
}

const header = new Vue({
    el: '#header',
    data: {
    	sideMenu: false,
    	classes: []
    }
});
