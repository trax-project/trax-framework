<template>

    <span class="bmd-form-group">
        <div class="row" v-if="col">
            <label :class="labelClass">
            </label>
            <div :class="formColClass">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" 
                            :disabled="disabled" 
                            :checked="value"
                            @input="changed($event)"
                        >
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                        {{ text }}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-check" v-else>
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" 
                    :disabled="disabled" 
                    :checked="value"
                    @input="changed($event)"
                >
                <span class="form-check-sign">
                    <span class="check"></span>
                </span>
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

        created: function() {
            if (!this.col) return;
            var parts = this.col.split('-');
            this.colBreak = parts[0];
            this.colSize = parts[1];
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

        methods: {

            changed(event) {
                this.$emit('input', event.target.checked);
                this.$emit('change', event.target.checked);
            }
        }
    }
</script>
