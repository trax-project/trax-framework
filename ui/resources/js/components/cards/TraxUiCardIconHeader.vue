<template>

    <div class="card">
        <div :class="'card-header trax-card-icon-with-actions card-header-icon card-header-'+headerColor">
            <div class="card-icon">
                <i class="material-icons"> {{ icon }} </i>
            </div>
            <div class="trax-card-actions">
                <button class="btn btn-link" v-for="action in actions" :key="action.icon" :id="action.event" @click="callAction">
                    <i :class="'material-icons text-' + headerColor">{{ action.icon }}</i>
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
            icon: null,
            title: null,
            headerColor: {default: 'primary'},
            actions: null,
            bus: null
        },

        methods: {
        
            callAction(event) {
                this.bus.$emit(event.currentTarget.id);
            }
        }
    }
</script>
