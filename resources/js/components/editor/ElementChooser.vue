<template>
    <div class="element-chooser" :class="{ choosing: showDropdown }" v-on:click="showDropdown = !showDropdown" v-if="currentElement">
        <span class="colour" v-bind:style="{ 'background-color': elementColour(currentElement) }"></span>
        <h3 v-html="currentElement.name"></h3>
        <h4>
            {<span v-html="currentElement.element"></span>}
        </h4>
        <ul v-if="showDropdown">
            <li v-for="e in elements" v-if="!e.value" v-on:click="chooseElement(e)">
                <span class="colour" v-bind:style="{ 'background-color': elementColour(e) }"></span>
                <span class="name" v-html="e.name ? e.name : e.element"></span>
            </li>
        </ul>
        <i class="fal fa-chevron-down"></i>
    </div>
</template>

<script>
    export default {
        props: [
            'currentElement', 'elements', 'theme'
        ],
        data (){
            return {
                showDropdown: false
            }
        },
        watch: {


        },
        methods: {

            chooseElement (element) {

                this.$emit('select', element);

            },

            elementColour (el) {

                if(el.r){
                    return 'rgb(' + el.r + ', ' + el.g + ', ' + el.b + ')';
                } else {
                    return '#FFF';
                }

            }

        }
    }
</script>
