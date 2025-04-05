require('./app.js');
window.axios = require('axios');

import Helpers from './helpers.js';

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type':'application/json',
    'Accept':'application/json'
};

Vue.component('element-chooser', require('./components/editor/ElementChooser.vue').default);
Vue.component('colour-picker', require('./components/editor/ColourPicker.vue').default);
Vue.component('radio', require('./components/InputRadio.vue').default);

Vue.component('theme-classes', {
    render: function (createElement) {
        return createElement('style', this.$slots.default)
    }
});

Object.filter = (obj, predicate) =>
    Object.keys(obj)
          .filter( key => predicate(obj[key]) )
          .reduce( (res, key) => Object.assign(res, { [key]: obj[key] }), {} );

const editor = new Vue({
    el: '#editor',
    data: {
        init: false,
    	loading: true,
    	saving: false,
        changes: false,
        choosingElement: false,
        paletteUpdate: 1,
        pickerMode: 'picker',
        elementSearch: '',
        linkCopied: false,
        highlightElement: false,

        welcome: {
            show: false,
            section: 'New',
            recent: [],
            importing: false,
            importError: ''
        },

        dialog: {
            new: false,
            save: false,
            share: false,
            download: false
        },

        download: {
            title: 'Preparing your download',
            count: 0,
            percent: 0,
            timer: null
        },

        view: {
            viewIndex: 0,
            currentView: 'Arrangement',
            availableViews: [
                'Arrangement', 'Automation', 'Preferences', 'Session', 'Audio Editor', 'Midi Editor'
            ]
        },

        preview: {
            width: null,
            height: null,
            dragging: false,
            startX: null,
            startY: null,
            scrollLeft: null,
            scrollTop: null
        },

        history: [],
        historyPosition: -1,
        historyProcessing: false,
        historyColourTimer: null,
        historyLastColour: null,

        zoom: {
            show: false,
            current: {
                value: 1,
                width: 1440,
                height: 900,
                top: 'auto',
                left: 'auto',
                transform: 'translate(0%,0%)'
            },
            base: {
                width: 1440,
                height: 900
            },
            levels: []
        },

    	theme_id: theme_id,
  		theme: null,
        currentElement: null,

        coloursGlobal: [],
        coloursUser: []
    },
    created() {

        window.addEventListener("resize", this.windowResize);

    },
    destroyed() {

        window.removeEventListener("resize", this.windowResize);

    },
    mounted (){

        if( !this.theme_id ){
            this.welcome.show = true;
        }

        // Build Zoom Levels
        const levels = [0.25, 0.5, 0.6, 0.7, 0.8, 0.9, 1, 1.25, 1.5, 1.75, 2, 3, 4, 5];

        levels.forEach(level => {

            this.zoom.levels.push ({
                name:  (level * 100) + '%',
                value: level,
                width: this.zoom.base.width * level,
                height: this.zoom.base.height * level
            });

        });

    	this.loadTheme(0);

        this.$nextTick(() => {

            this.windowResize();

            var zoomLevel = 0.25;

            this.zoom.levels.forEach(l => {

                if( this.preview.width > l.width && this.preview.height > l.height ){
                    zoomLevel = l.value;
                }

            });

            this.zoomPreview(zoomLevel);

        });

        const preview = document.getElementById('preview-inner');

        preview.addEventListener('mousedown', (e) => {
            this.preview.dragging = true;
            preview.classList.add('active');
            this.preview.startX = e.pageX - preview.offsetLeft;
            this.preview.startY = e.pageY - preview.offsetTop;
            this.preview.scrollLeft = preview.scrollLeft;
            this.preview.scrollTop = preview.scrollTop;
        });
        preview.addEventListener('mouseleave', () => {
            this.preview.dragging = false;
            preview.classList.remove('active');
        });
        preview.addEventListener('mouseup', () => {
            this.preview.dragging = false;
            preview.classList.remove('active');
        });
        preview.addEventListener('mousemove', (e) => {
            if(!this.preview.dragging) return;
            e.preventDefault();
            const x = e.pageX - preview.offsetLeft;
            const y = e.pageY - preview.offsetTop;
            const walkX = (x - this.preview.startX) * 1; //scroll-fast
            const walkY = (y - this.preview.startY) * 1; //scroll-fast
            preview.scrollLeft = this.preview.scrollLeft - walkX;
            preview.scrollTop = this.preview.scrollTop - walkY;
        });

    },
    computed: {

        filteredElements () {

            if( !this.theme ){
                return [];
            }

            console.log('test1');

            var filtered = [];

            for(var el in this.theme.elements){
                filtered.push(this.theme.elements[el]);
            }

            console.log('test2');

            if( this.elementSearch !== '' ){

                return filtered.filter(el => {
                    return el.name.toLowerCase().match( this.elementSearch.toLowerCase() ) ||
                           el.element.toLowerCase().match( this.elementSearch.toLowerCase() )
                }).sort((a,b) => {
                    var elementA = a.element.toUpperCase();
                    var elementB = b.element.toUpperCase();
                    return (elementA < elementB) ? -1 : (elementA > elementB) ? 1 : 0;
                });

            } else {

                return filtered.sort((a,b) => {
                    var elementA = a.element.toUpperCase();
                    var elementB = b.element.toUpperCase();
                    return (elementA < elementB) ? -1 : (elementA > elementB) ? 1 : 0;
                });

            }


        },

        currentZoomLevel () {

            return this.zoom.levels.find(l => l.value == this.zoom.current.value);

        }

    },
    watch: {

        //
        // Welcome Section
        //
        'welcome.section': function(now, prev){

            if( now == 'Recent' ){
                this.loadRecentThemes();
            }

        },

        //
        // Colour 1
        //
        'theme.elements.ControlForeground.r': function(now, prev) {

            var hsl = Helpers.rgbToHsl(
                this.theme.elements.ControlForeground.r,
                this.theme.elements.ControlForeground.g,
                this.theme.elements.ControlForeground.b
            );

            if( hsl[2] >= .40 && hsl[2] <= .70 ){
                this.theme.colour_1 = 260 * hsl[0];
            } else {
                this.theme.colour_1 = null;
            }

        },

        //
        // Colour 2
        //
        'theme.elements.RetroDisplayForeground.r': function(now, prev) {

            var hsl = Helpers.rgbToHsl(
                this.theme.elements.RetroDisplayForeground.r,
                this.theme.elements.RetroDisplayForeground.g,
                this.theme.elements.RetroDisplayForeground.b
            );

            if( hsl[2] >= .40 && hsl[2] <= .70 ){
                this.theme.colour_2 = 360 * hsl[0];
            } else {
                this.theme.colour_2 = null;
            }

        },

        //
        // Lightness
        //
        'theme.elements.SurfaceBackground.r': function(now, prev) {

            var hsl = Helpers.rgbToHsl(
                this.theme.elements.SurfaceBackground.r,
                this.theme.elements.SurfaceBackground.g,
                this.theme.elements.SurfaceBackground.b
            );

            this.theme.lightness = hsl[2] * 100;

        },

        //
        // Current Element
        //
        'currentElement.element': function(now, prev){

            this.historyLastColour = Object.assign({}, this.currentElement);

        },

        //
        // View
        //
        'view.currentView': function(now, prev){

            this.addHistory({
                action: 'currentView',
                before: prev,
                after: now
            });

        }

    },
    methods: {

        //
        // Resize
        //
        windowResize () {

            this.preview.width = document.getElementById('preview').offsetWidth;
            this.preview.height = document.getElementById('preview').offsetHeight;

            this.repositionPreview();

        },

        //
        // Remix from Welcome
        //
        remixFromWelcome ( theme_id ){

            if( this.changes && !confirm('There are unsaved changes to the current theme, are remix a new one?') ){
                return false;
            }

            this.loadTheme( theme_id );

            this.welcome.show = false;

        },

        //
        // Load from Welcome
        //
        loadFromWelcome ( theme_id ){

            if( this.changes && !confirm('There are unsaved changes to the current theme, are you sure you load another theme?') ){
                return false;
            }

            this.theme_id = theme_id;

            this.loadTheme(0);

            this.welcome.show = false;

        },

        //
        // Load Recent Themes
        //
        loadRecentThemes (){

            axios.get('/editor/recent')
                .then( (response) => {
                    this.welcome.recent = response.data;
                })
                .catch( (error) => {
                    alert('There was a problem loading your recent themes!');
                });

        },

        //
        // Import Theme
        //
        importTheme (e) {

            let file = e.target.files[0]
            let fd = new FormData();

            fd.append('file', file);

            this.welcome.importing = true;
            this.welcome.importError = '';

            axios.post('/editor/import', fd)
                .then( (response) => {

                    this.welcome.importing = false;

                    this.theme = response.data;

                    this.currentElement = this.theme.elements.Desktop;

                    this.welcome.show = false;

                    this.$nextTick( () => {
                        this.changes = false;
                    });

                })
                .catch( (error) => {

                    if(error.response.status == 413){

                        this.welcome.importError = 'File size too large!';

                    } else if(error.response.status == 505){

                        this.welcome.importError = error.response.data.message;

                    } else if(error.response.status == 422){

                        var errors = [];
                        for (const [key, error] of Object.entries(error.response.data.errors)) {
                            error.forEach(e => {
                                errors.push(e);
                            });
                        }

                        this.welcome.importError = errors.join('<br>');

                    }

                    this.welcome.importing = false;

                });

        },

        //
        // File Commands
        //
    	loadTheme ( remix_id ){

            this.loading = true;

    		axios.post('/editor/load', {
                    theme_id: this.theme_id,
                    remix_id: remix_id
                })
                .then( (response) => {

                	this.loading = false;

                	this.theme = response.data.theme;

                    this.coloursGlobal = response.data.coloursGlobal;
                    this.coloursUser = response.data.coloursUser;

                	this.currentElement = this.theme.elements.Desktop;

                    this.$nextTick( () => {
                        this.changes = false;
                    });

                    setTimeout(() => {
                        this.init = true;
                    }, 800);

                })
                .catch( (error) => {
                	this.loading = false;

                    if(error.response.status == 404){

                        alert('This theme has been removed, you can not edit it');

                        window.location.href = '/my-themes';

                    } else {

                        alert('There was a problem loading the theme!');

                    }

                });

    	},

        saveTheme (){

            this.saving = true;

            axios.post('/editor/save', {
                    theme: this.theme
                })
                .then( (response) => {

                    this.saving = false;

                    if( !response.data.id ){
                        alert('There was a problem saving the theme!');
                        return false;
                    }

                    this.changes = false;

                    this.theme.id = response.data.id;
                    this.theme.url = response.data.url;
                    this.theme.updated = false;
                    this.dialog.save = false;

                    history.pushState(null, '', '/editor/' + this.theme.id);

                })
                .catch( (error) => {

                    if(error.response.status == 404){

                        alert('This theme has been removed, you can not save it');

                        window.location.href = '/my-themes';

                    } else {

                        alert('There was a problem saving the theme!');

                    }

                    this.saving = false;

                });

        },

        downloadTheme (){

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

        viewTheme () {

            if( this.theme.url ){

                window.open( this.theme.url );

            }

        },

        //
        // Share
        //
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

        },

        //
        // Select Element
        //
        elementColour (el) {

            if(el.r){
                return 'rgb(' + el.r + ', ' + el.g + ', ' + el.b + ')';
            } else {
                return '#FFF';
            }

        },

        //
        // Picker
        //

        updateColour (colour) {

            this.changes = true;

            if( !this.historyLastColour ){
                this.historyLastColour = Object.assign({}, this.currentElement);
            }

            this.currentElement.r = colour.r;
            this.currentElement.g = colour.g;
            this.currentElement.b = colour.b;
            this.currentElement.a = colour.a * 255;

            this.updateStyles();

            clearTimeout( this.historyColourTimer );

            this.historyColourTimer = setTimeout(() => {

                if(
                   this.historyLastColour.element != this.currentElement &&
                   (this.historyLastColour.r != this.currentElement.r || this.historyLastColour.g != this.currentElement.g || this.historyLastColour.b != this.currentElement.b)
                ){

                    this.addHistory({
                        action: 'changeColour',
                        before: this.historyLastColour,
                        after: Object.assign({}, this.currentElement)
                    });

                }

                this.historyLastColour = Object.assign({}, this.currentElement);

            }, 500);

        },

        updateStyles () {

            this.currentElement.rgba = 'rgba(' + this.currentElement.r + ',' + this.currentElement.g + ',' + this.currentElement.b + ',' + this.alphaToOpacity(this.currentElement.a) + ')';

        },

        alphaToOpacity (alpha) {

            return alpha / 255;

        },

        //
        // Palette
        //

        addToPallete (colour){

            var index = this.coloursUser.findIndex(c => c.r == colour.r && c.g == colour.g && c.b == colour.b);

            if( index == -1 ){

                this.coloursUser.unshift({
                    r: colour.r,
                    g: colour.g,
                    b: colour.b
                });

                axios.post('/editor/colours/store', {
                        r: colour.r,
                        g: colour.g,
                        b: colour.b
                    })
                    .catch( (error) => {
                        this.loading = false;
                        alert('There was a problem saving your colour!');
                    });

            }

        },

        loadFromPallete (colour){

            var mode = this.pickerMode;

            this.updateColour({
                r: colour.r,
                g: colour.g,
                b: colour.b,
                a: 1
            });

            this.paletteUpdate++;

            this.$nextTick( () => {

                this.pickerMode = mode;

            });

        },

        invertColour (colour){

            var inverted = Helpers.invertRgb(colour.r, colour.g, colour.b);

            this.loadFromPallete({
                r: inverted[0],
                g: inverted[1],
                b: inverted[2]
            });

        },

        invertTheme () {

            Object.keys(this.theme.elements).forEach( key => {

                if( this.theme.elements[key].r ){

                    var inverted = Helpers.invertRgb(this.theme.elements[key].r, this.theme.elements[key].g, this.theme.elements[key].b);

                    this.theme.elements[key].r = inverted[0];
                    this.theme.elements[key].g = inverted[1];
                    this.theme.elements[key].b = inverted[2];
                    this.theme.elements[key].rgba = 'rgba(' + inverted[0] + ',' + inverted[1] + ',' + inverted[2] + ')';

                }

            });

        },

        //
        // Preview
        //

        selectElement( element ){

            if( this.theme.elements[ element ] ){

                this.currentElement = this.theme.elements[ element ];

            }

        },

        zoomIn () {

            if( this.zoom.current.value < 3 ){

                var i = this.zoom.levels.findIndex(l => l.value == this.zoom.current.value);

                this.zoomPreview( this.zoom.levels[ i + 1 ].value );

            }

        },

        zoomOut () {

            if( this.zoom.current.value > 0.25 ){

                var i = this.zoom.levels.findIndex(l => l.value == this.zoom.current.value);

                this.zoomPreview( this.zoom.levels[ i - 1 ].value );

            }

        },

        repositionPreview () {

            if( this.preview.width > this.zoom.current.width && this.preview.height > this.zoom.current.height ){
                this.zoom.current.top = '50%';
                this.zoom.current.left = '50%';
                this.zoom.current.transform = 'translate(-50%,-50%)';

            } else if( this.preview.width > this.zoom.current.width){
                this.zoom.current.top = 'auto';
                this.zoom.current.left = '50%';
                this.zoom.current.transform = 'translate(-50%,0%)';

            } else if(this.preview.height > this.zoom.current.height ){
                this.zoom.current.top = '50%';
                this.zoom.current.left = 'auto';
                this.zoom.current.transform = 'translate(0%,-50%)';

            } else {
                this.zoom.current.top = 'auto';
                this.zoom.current.left = 'auto';
                this.zoom.current.transform = 'translate(0%,0%)';

            }

        },

        zoomPreview (value) {

            this.zoom.show = false;

            // Get Current Scroll Position
            var scrollLeft = document.getElementById('preview-inner').scrollLeft;
            var scrollTop = document.getElementById('preview-inner').scrollTop;

            if( this.zoom.current.width > this.preview.width ){
                var scrollMax = (this.zoom.current.width - this.preview.width) + 20;
                var currentScrollXPercent = (scrollLeft / scrollMax);
            } else {
                var currentScrollXPercent = 0.5;
            }

            if( this.zoom.current.height > this.preview.height ){
                var scrollMax = (this.zoom.current.height - this.preview.height) + 20;
                var currentScrollYPercent = (scrollTop / scrollMax);
            } else {
                var currentScrollYPercent = 0.5;
            }

            // Set New Zoom Level
            this.zoom.current.value = parseFloat(value);

            this.zoom.current.width = this.zoom.base.width * this.zoom.current.value;
            this.zoom.current.height = this.zoom.base.height * this.zoom.current.value;

            this.repositionPreview();

            // Set New Scroll Position
            this.$nextTick(() => {

                document.getElementById('preview-inner').scrollLeft = ((this.zoom.current.width - this.preview.width) + 20) * currentScrollXPercent;
                document.getElementById('preview-inner').scrollTop = ((this.zoom.current.height - this.preview.height) + 20) * currentScrollYPercent;

            });


        },

        //
        // History
        //
        addHistory (obj) {

            if( this.historyProcessing ){
                this.historyProcessing = false;
                return true;
            }

            if( this.history.length > (this.historyPosition + 1) ){

                const toRemove = this.history.length - (this.historyPosition + 1);

                for(var i = 0; i < toRemove; i++){
                    this.history.pop();
                }
            }

            this.history.push(obj);

            this.historyPosition++;

        },

        historyAction (item, forward){

            // Change View
            if( item.action == 'currentView' ){
                this.view.currentView = forward ? item.after : item.before;

            // Change Colour
            } else if( item.action == 'changeColour' ){

                // Select Element
                this.currentElement = this.theme.elements[ forward ? item.after.element : item.before.element ];

                this.loadFromPallete({
                    r: forward ? item.after.r : item.before.r,
                    g: forward ? item.after.g : item.before.g,
                    b: forward ? item.after.b : item.before.b
                });

            }

        },

        undo () {

            if( this.historyPosition > -1 ){

                this.historyProcessing = true;

                var item = this.history[ this.historyPosition ];

                this.historyAction(item, false);

                this.historyPosition--;

            }

        },

        redo () {

            if( this.historyPosition < (this.history.length - 1) ){

                this.historyProcessing = true;

                this.historyPosition++;

                var item = this.history[ this.historyPosition ];

                this.historyAction(item, true);

            }

        }

    }
});
