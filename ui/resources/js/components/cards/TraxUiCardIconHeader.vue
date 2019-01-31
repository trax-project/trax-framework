<template>

    <div class="card">
        <div :class="'card-header card-header-icon card-header-'+headerColor">
            <div class="card-icon">
                <i class="material-icons"> {{ icon }} </i>
            </div>
            <h4 class="card-title"> {{ title }} </h4>
        </div>
        <div class="card-body">
            <slot></slot>
        </div>
    </div>
    
</template>

<script>
    export default {
        props: {
            icon: null,
            title: null,
            headerColor: {default: 'primary'}
        }
    }
</script>
