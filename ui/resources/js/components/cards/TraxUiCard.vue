<template>

    <div :id="id" :class="cardClass">
        <div class="card-header pb-0" v-if="title">
            <h4 :class="titleClass" v-if="titleSmall">{{ title }}</h4>
            <h3 :class="titleClass" v-else>{{ title }}</h3>
        </div>
        <div :class="bodyClass">
            <slot></slot>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            title: null,
            titleBold: null,
            titleSmall: null,
            titleAlign: {default: 'left'},
            titleNoPadding: null,
            align: {default: 'left'},
            backgroundColor: null,
            sticky: null
        },

        data: function() {
            return {
                id: 'trax-ui-card-'+this.uuid(), 
            }
        },

        computed: {

            cardClass: function() {
                return this.backgroundColor ? 'card mt-2 bg-'+this.backgroundColor : 'card mt-2';
            },

            bodyClass: function() {
                var res = 'card-body text-'+this.align;
                if (this.titleNoPadding) res += ' pt-0';
                return res;
            },

            titleClass: function() {
                var res = 'card-title text-'+this.titleAlign;
                if (this.titleBold) res += ' font-weight-bold';
                return res;
            }
        },

        mounted: function() {
            if (this.sticky) $('#'+this.id).sticky({topSpacing:0});
        },

        methods: {

            uuid() {
                var res = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                    return v.toString(16);
                });
                return res;
            }
        }
    }

</script>
