<template>
    <div class="colour-picker">

        <div class="current" v-bind:style="{ 'background-color': current }"></div>

        <div class="sliders">

            <div class="mode-outer">
                <ul class="mode">
                    <li @click="mode = 'picker'" :class="{ active: mode == 'picker' }">PICKER</li><li @click="mode = 'rgb'" :class="{ active: mode == 'rgb' }">RGB</li><li @click="mode = 'hsl'" :class="{ active: mode == 'hsl' }">HSL</li><li v-if="currentElement.alpha" @click="mode = 'alpha'" :class="{ active: mode == 'alpha' }">Alpha</li>
                </ul>
            </div>

            <div id="picker"></div>

            <div class="inputs" v-if="colourPicker">

                <div class="input-hex" v-if="mode == 'picker'">
                    #<input type="text" placeholder="HEX" v-bind:value="colourPicker.color.hexString.replace('#','')" @keypress="hexInput($event)" @keyup="hexUpdate($event)">
                </div>

                <div class="input-rgb" v-if="mode == 'rgb'">
                    <input type="text" placeholder="R" v-bind:value="colourPicker.color.red" @keyup="numberUpdate($event, 'red', 0, 255)">
                    <input type="text" placeholder="G" v-bind:value="colourPicker.color.green" @keyup="numberUpdate($event, 'green', 0, 255)">
                    <input type="text" placeholder="B" v-bind:value="colourPicker.color.blue" @keyup="numberUpdate($event, 'blue', 0, 255)">
                </div>

                <div class="input-rgb" v-if="mode == 'hsl'">
                    <input type="text" placeholder="H" v-bind:value="colourPicker.color.hue" @keyup="numberUpdate($event, 'hue', 0, 360)">
                    <input type="text" placeholder="S" v-bind:value="colourPicker.color.saturation" @keyup="numberUpdate($event, 'saturation', 0, 100)">
                    <input type="text" placeholder="L" v-bind:value="colourPicker.color.value" @keyup="numberUpdate($event, 'value', 0, 100)">
                </div>

                <div class="input-rgb" v-if="mode == 'alpha'">
                    <input type="text" placeholder="A" v-bind:value="colourPicker.color.alpha" @keyup="numberUpdate($event, 'alpha', 0, 100)">
                </div>

            </div>

        </div>
    </div>
</template>

<script>

    import iro from '@jaames/iro';

    export default {
        props: [
            'currentElement', 'newMode'
        ],
        mounted (){

            this.updateCurrent();

            // Picker
            this.colourPicker = new iro.ColorPicker('#picker', {
                id: 'iropicker',
                width: 200,
                color: this.current,
                layoutDirection: 'horizontal',
                sliderSize: 40,
                layout: [{
                    component: iro.ui.Wheel
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'red'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'green'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'blue'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'hue'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'saturation'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'value'
                    }
                },
                {
                    component: iro.ui.Slider,
                    options: {
                        sliderType: 'alpha'
                    }
                }
                ]
            })

            this.colourPicker.on('color:change', (color) => {
                this.updateCurrent(false);
                this.$emit('change', color.rgba);
            });

            this.iroPicker = document.getElementById('iropicker');

            this.mode = 'picker';

        },
        data (){
            return {
                current: null,
                mode: null,
                colourPicker: null,
                iroPicker: null
            }
        },
        watch: {

            newMode (now, prev){

                this.mode = now;

            },

            currentElement (now, prev) {

                if( this.mode == 'alpha' && now.alpha == 0 ){
                    this.mode = 'picker';
                }

                this.updateCurrent(true);

            },

            mode (now, prev) {

                this.$emit('mode', now);

                this.$nextTick( () => {

                    this.iroPicker.childNodes.forEach( (child, index) => {

                        if(now == 'picker' && index == 0 ){
                            child.style.display = 'inline-block';

                        } else if(now == 'rgb' && (index >=1 && index <= 3) ){
                            child.style.display = 'inline-block';

                        } else if(now == 'hsl' && (index >=4 && index <= 6) ){
                            child.style.display = 'inline-block';

                        } else if(now == 'alpha' && (index == 7) ){
                            child.style.display = 'inline-block';

                        } else {
                            child.style.display = 'none';
                        }

                    });

                });

            }

        },
        methods: {

            updateCurrent (updatePicker){

                this.current = 'rgba(' + this.currentElement.r + ',' + this.currentElement.g + ',' + this.currentElement.b + ',' + (this.currentElement.a / 255) + ')';

                if( updatePicker ){
                    this.colourPicker.color.rgbString = this.current;
                }

            },

            hexInput (event){

                var inp = String.fromCharCode(event.keyCode);

                if ( /[a-zA-Z0-9#]/.test(inp) && event.target.value.length < 6 ){
                    // Accept
                } else {
                    event.preventDefault();
                }

            },

            hexUpdate (event){

                if( event.target.value.length == 6 ){
                    this.colourPicker.color.hexString = event.target.value;
                    this.updateCurrent(false);
                }

            },

            numberUpdate (event, colour, min, max){

                event.target.value = parseInt(event.target.value);

                if( isNaN(event.target.value) || event.target.value < min ){
                    event.target.value = min;
                } else if( event.target.value > max ){
                    event.target.value = max;
                }

                this.colourPicker.color[colour] = event.target.value;

            }

        }
    }
</script>
