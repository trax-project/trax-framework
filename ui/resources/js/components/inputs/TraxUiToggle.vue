<template>

    <span class="bmd-form-group">
        <div class="row" v-if="col">
            <label :class="labelClass">
            </label>
            <div :class="formColClass">
                <div class="togglebutton">
                    <label>
                        <input type="checkbox"
                            :disabled="disabled" 
                            :checked="value"
                            @input="$emit('input', $event.target.checked)"
                        >
                        <span class="toggle"></span>
                        {{ text }}
                    </label>
                </div>
            </div>
        </div>
        <div class="togglebutton" v-else>
            <label>
                <input type="checkbox"
                    :disabled="disabled" 
                    :checked="value"
                    @input="$emit('input', $event.target.checked)"
                >
                <span class="toggle"></span>
                {{ text }}
            </label>
        </div>
    </span>
    
</template>

<script>
    export default {

        props: ['text', 'value', 'col', 'disabled'],

        data() {
            return {
                colBreak: null,
                colSize: null
            }
        },

        computed: {

            labelClass: function() {
                if (!this.col) return '';
                return 'col-'+this.col;
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
