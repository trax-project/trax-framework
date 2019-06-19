<template>

    <div class="card">
        <div :class="'card-header trax-card-with-actions card-header-'+headerColor">
            <div class="trax-card-actions">
                <button class="btn btn-link text-white" v-for="action in actions" :key="action.icon" :id="action.event" @click="callAction">
                    <i class="material-icons">{{ action.icon }}</i>
                </button>
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
            title: null,
            actions: null,
            bus: null,
            headerColor: {default: 'primary'}
        },

        methods: {
        
            callAction(event) {
                this.bus.$emit(event.currentTarget.id);
            }
        }
    }
</script>
