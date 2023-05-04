<template>
    <div class="anb">
        <img src="/img/brand/anb_new_logo.jpg" width="300px">  
    </div>
</template>

<script>
    export default {
        props: {
            size: {
                type: String,
                default: 'is-small',
            }
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        methods: {
            //
        }
    }
</script>

<style lang="sass" scoped>
    .anb
        display: flex
        justify-content: center
        align-items: center
        height: 100%
        min-height:80vh

    .svg
        margin: 0 auto
        display: block

    .is-small
        width: 80px

    .is-medium
        width: 110px

    .is-large
        width: 150px


    @keyframes fadeColor
        0%
            fill: #FFFFFF
        100%
            fill: #ff3c00

    .st0
        fill: #1690CC

    .st1
        fill: #E04248
        -webkit-animation: fadeColor 2s linear infinite alternate
        -moz-animation: fadeColor 2s linear infinite alternate
        animation: fadeColor 2s linear infinte alternate
</style>
