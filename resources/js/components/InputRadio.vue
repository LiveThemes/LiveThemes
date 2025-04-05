<template>
    <label class="radio">
        <input type="radio" :name="name" :value="value" :checked="checked" @click="changeValue"> <slot></slot>
        <span class="check" :class="icon"></span>
    </label>
</template>

<script>
    export default {
        props: [
            'name', 'value', 'checked', 'icon'
        ],
        methods: {

            changeValue (){

                this.$emit('change', this.value);

            }

        }
    }
</script>
