<template>

    <span class="bmd-form-group">

        <div class="input-group" v-if="icon">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">{{ icon }}</i>
                    <span class="trax-required" v-if="required">*</span>
                </span>
            </div>
            <span class="form-control trax-form-control-select">

                <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-if="grouped">
                    <option value="" disabled selected v-if="placeholder">> {{ placeholder }}</option>
                    <option value="" selected v-if="unselected"> {{ unselected }}</option>
                    <optgroup v-for="(group, name) in options" :key="name" :label="name">
                        <option v-for="option in group" :key="option.value" :value="option.value">
                            {{ option.name }}
                        </option>
                    </optgroup>
                </select>
                
                <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-else>
                    <option value="" disabled selected v-if="placeholder">> {{ placeholder }}</option>
                    <option value="" selected v-if="unselected"> {{ unselected }}</option>
                    <option v-for="option in options" :key="option.value" :value="option.value">
                        {{ option.name }}
                    </option>
                </select>
                
            </span>
        </div>
        
        <div class="row" v-else-if="col">
            <label :class="labelClass" style="padding-top:1.7rem;">
                {{ placeholder }}
                <span class="trax-required" v-if="required">*</span>
            </label>
            <div :class="formColClass">
                <span class="form-control trax-form-control-select">

                    <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-if="grouped">
                        <option value="" selected v-if="unselected"> {{ unselected }}</option>
                        <optgroup v-for="(group, name) in options" :key="name" :label="name">
                            <option v-for="option in group" :key="option.value" :value="option.value">
                                {{ option.name }}
                            </option>
                        </optgroup>
                    </select>
                    
                    <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-else>
                        <option value="" selected v-if="unselected"> {{ unselected }}</option>
                        <option v-for="option in options" :key="option.value" :value="option.value" >
                            {{ option.name }}
                        </option>
                    </select>
            
                </span>
                <span class="trax-form-error" v-if="error"> {{ error }} </span>
            </div>
        </div>
        <div v-else>
            <span class="form-control trax-form-control-select">

                <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-if="grouped">
                    <option value="" disabled selected v-if="placeholder">> {{ placeholder }}</option>
                    <option value="" selected v-if="unselected"> {{ unselected }}</option>
                    <optgroup v-for="(group, name) in options" :key="name" :label="name">
                        <option v-for="option in group" :key="option.value" :value="option.value">
                            {{ option.name }}
                        </option>
                    </optgroup>
                </select>
                
                <select :id="id" class="selectpicker" data-style="select-with-transition" :disabled="disabled" :multiple="multiple" v-else>
                    <option value="" disabled selected v-if="placeholder">> {{ placeholder }}</option>
                    <option value="" selected v-if="unselected"> {{ unselected }}</option>
                    <option v-for="option in options" :key="option.value" :value="option.value" >
                        {{ option.name }}
                    </option>
                </select>
                
            </span>
            <span class="trax-form-error" v-if="error"> {{ error }} </span>
        </div>
        <span class="trax-form-error" v-if="error && icon"> {{ error }} </span>
    </span>
    
</template>

<script>
    export default {

        props: {
            error: null,
            icon: null,
            col: null,
            options: null,
            value: null,
            disabled: null,
            insideLabel: null,
            placeholder: null,
            unselected: null,
            multiple: null,
            grouped: null,
            required: null,
            noneSelectedText: {default: lang.trax_ui.form.nothing_selected},
        },

        data() {
            return {
                id: 'trax-ui-select-'+this.uuid(), 
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
        },

        mounted() {
            var that = this;
            
            if (that.multiple) {
                $('.selectpicker#'+this.id).selectpicker({
                    iconBase: 'material-icons',
                    noneSelectedText: that.noneSelectedText
                });
            }

            $('.selectpicker#'+this.id).on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
                if (that.multiple) {
                    that.$emit('input', $(this).val());
                } else {
                    that.$emit('input', this.value);
                }
            });

            // Force initial value.
            $('.selectpicker#'+this.id).selectpicker('refresh');
            $('.selectpicker#'+this.id).selectpicker('val', this.value);
        },

        updated: function () {
            if (this.multiple) {
                // Check this case !!!!!!!!!
            } else {
                $('.selectpicker#'+this.id).selectpicker('refresh');
                $('.selectpicker#'+this.id).selectpicker('val', this.value);
            }
        },

        watch: {
            value: function (value) {
                if (this.multiple) {
                    if (value) {
                        $('.selectpicker#'+this.id).selectpicker('val', value);
                    } else {
                        $('.selectpicker#'+this.id).selectpicker('val', []);
                    }
                } else {
                    if (value) {
                        $('.selectpicker#'+this.id).selectpicker('val', value);
                    } else {
                        $('.selectpicker#'+this.id).selectpicker('val', "");
                    }
                }
            }
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
