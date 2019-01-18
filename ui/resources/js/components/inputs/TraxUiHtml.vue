<template>

    <div class="input-group" v-if="icon">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="material-icons">{{ icon }}</i>
            </span>
        </div>
        <span style="white-space:pre-wrap;" v-html="value"></span>
    </div>

    <div class="row pt-2 pb-3" v-else-if="col">
        <label :class="labelClass" style="padding-top:2px;">
            {{ placeholder }}
        </label>
        <div :class="formColClass" style="white-space:pre-wrap;" v-html="value">
        </div>
    </div>

    <div class="row" v-else>
        <div class="col" style="white-space:pre-wrap;" v-html="value"></div>
    </div>
    
</template>

<script>
    export default {

        props: ['icon', 'col', 'placeholder', 'value'],

        data() {
            return {
                colBreak: null,
                colSize: null
            }
        },

        computed: {

            labelClass: function() {
                if (!this.col) return '';
                return 'col-'+this.col+' text-left text-'+this.colBreak+'-right pl-3';
            },

            formColClass: function() {
                if (!this.col) return '';
                return 'col-'+this.colBreak+'-'+(12-this.colSize);
            }
        },

        created: function() {
            if (!this.col) return;
            var parts = this.col.split('-');
            this.colBreak = parts[0];
            this.colSize = parts[1];
        }

    }
</script>
