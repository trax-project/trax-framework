<template>

    <div class="card card-login">
        <div :class="'card-header card-header-'+headerColor+' text-'+align">
            <h4 class="card-title"> {{ title }} </h4>
        </div>
        <div class="card-body pb-3">
            <slot></slot>
        </div>
    </div>
    
</template>

<script>
    export default {
        props: {
            title: null,
            align:  {default: 'center'},
            headerColor: {default: 'primary'}
        }
    }
</script>
