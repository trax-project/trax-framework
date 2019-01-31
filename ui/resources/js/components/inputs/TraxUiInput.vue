<template>

    <span class="bmd-form-group">
        <div class="input-group" v-if="icon">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">{{ icon }}</i>
                    <span class="trax-required" v-if="required">*</span>
                </span>
            </div>
            <textarea class="form-control" v-if="rows"
                    :type="type" 
                    :step="step" 
                    :placeholder="placeholder" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    :rows="rows"
                    @input="$emit('input', $event.target.value)"
            ></textarea>
            <input class="form-control" v-else
                    :type="type" 
                    :step="step" 
                    :placeholder="placeholder" 
                    :maxlength="maxlength" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    @input="$emit('input', $event.target.value)"
            >
            <span class="bmd-help" style="margin-top:55px;margin-left:55px;" v-if="help"> {{ help }} </span>
        </div>
        <div class="row" v-else-if="col">
            <label :class="labelClass" style="padding-top:1.7rem;">
                {{ placeholder }}
                <span class="trax-required" v-if="required">*</span>
            </label>
            <div :class="formColClass">
                <textarea class="form-control" v-if="rows" 
                    :type="type" 
                    :step="step" 
                    :maxlength="maxlength" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    :rows="rows"
                    @input="$emit('input', $event.target.value)"
                ></textarea>
                <input class="form-control" v-else 
                    :type="type" 
                    :step="step" 
                    :maxlength="maxlength" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    @input="$emit('input', $event.target.value)"
                >
                <span class="bmd-help" v-if="help"> {{ help }} </span>
                <span class="trax-form-error" v-if="error"> {{ error }} </span>
            </div>
        </div>
        <div v-else>
            <textarea class="form-control" v-if="rows" 
                    :type="type" 
                    :step="step" 
                    :placeholder="placeholder" 
                    :maxlength="maxlength" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    :rows="rows"
                    @input="$emit('input', $event.target.value)"
            ></textarea>
            <input class="form-control" v-else 
                    :type="type" 
                    :step="step" 
                    :placeholder="placeholder" 
                    :maxlength="maxlength" 
                    :required="required" 
                    :disabled="disabled" 
                    :autofocus="autofocus"
                    :value="value"
                    @input="$emit('input', $event.target.value)"
            >
            <span class="bmd-help" v-if="help"> {{ help }} </span>
            <span class="trax-form-error" v-if="error"> {{ error }} </span>
        </div>
        <span class="trax-form-error" v-if="error && icon"> {{ error }} </span>
    </span>
    
</template>

<script>
    export default {

        props: ['type', 'step', 'icon', 'col', 'placeholder', 'rows', 'value', 'error', 'help', 'maxlength', 'required', 'disabled', 'autofocus'],

        data() {
            return {
                colBreak: null,
                colSize: null
            }
        },

        computed: {

            labelClass: function() {
                if (!this.col) return '';
                return 'col-form-label col-'+this.col+' text-left text-'+this.colBreak+'-right pl-3';
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
