<template>

    <span class="bmd-form-group">

        <div class="input-group" v-if="icon">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">{{ icon }}</i>
                    <span class="trax-required" v-if="required">*</span>
                </span>
            </div>
            <div :class="choiceClass" :id="internal_id">
                <label v-for="option in options" :key="option.value" :for="internal_id+'-'+option.value">
                    <input type="radio" :id="internal_id+'-'+option.value" :name="internal_id" autocomplete="off"
                        :value="option.value"
                        :disabled="disabled" 
                        :checked="option.value == value"
                        @input="changed($event, option)"
                        @change="changed($event, option)"
                        @click="clicked($event, option)"
                    >
                    <span v-html="optionLabel(option)"></span>
                </label>
            </div>
        </div>

        <div class="row" v-else-if="col">
            <label :class="labelColClass" style="padding-top:1.7rem;">
                {{ placeholder }}
                <span class="trax-required" v-if="required">*</span>
            </label>
            <div :class="formColClass">
                <div :class="choiceClass" :id="internal_id">
                    <label v-for="option in options" :key="option.value" :for="internal_id+'-'+option.value">
                        <input type="radio" :id="internal_id+'-'+option.value" :name="internal_id" autocomplete="off"
                            :value="option.value"
                            :disabled="disabled" 
                            :checked="option.value == value"
                            @input="changed($event, option)"
                            @change="changed($event, option)"
                            @click="clicked($event, option)"
                        >
                        <span v-html="optionLabel(option)"></span>
                    </label>
                </div>
                <span class="trax-form-error" v-if="error"> {{ error }} </span>
            </div>
        </div>

        <div :class="formCheckClass" v-else>
            <div :class="choiceClass" :id="internal_id">
                <label v-for="option in options" :key="option.value" :for="internal_id+'-'+option.value">
                    <input type="radio" :id="internal_id+'-'+option.value" :name="internal_id" autocomplete="off"
                        :value="option.value"
                        :disabled="disabled" 
                        :checked="option.value == value"
                        @input="changed($event, option)"
                        @change="changed($event, option)"
                        @click="clicked($event, option)"
                    >
                    <span v-html="optionLabel(option)"></span>
                </label>
            </div>
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
            required: null,
            placeholder: null,
            size: {default: 'lg'},
            plainIcons: null,
            allowUnselect: null,
        },

        data() {
            return {
                internal_id: 'trax-ui-choice-'+this.uuid(), 
                colBreak: null,
                colSize: null
            }
        },

        computed: {

            formCheckClass: function() {
                return 'form-check trax-'+this.size;
            },

            choiceClass: function() {
                var res = 'btn-groupe btn-group-toggle trax-form-control-choice trax-'+this.size;
                if (this.plainIcons) res += ' trax-plain-icons';
                return res;
            },

            labelColClass: function() {
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

        mounted: function() {
            this.setLabel(this.value);
        },
        
        watch: {
            value: function(newValue) {
                this.setLabel(newValue);
            }
        },

        methods: {

            optionLabel(option) {
                var res = '';
                if (option.icon) res += '<i class="material-icons">'+option.icon+'</i>';
                if (option.name) res += option.name;
                return res;
            },

            changed(event, option) {
                this.$emit('input', event.target.value);
                this.$emit('change', event.target.value);
            },

            clicked(event, option) {
                if (this.value == event.target.value && this.allowUnselect) {
                    this.$emit('input', null);
                    this.$emit('change', null);
                }
            },

            setLabel(newValue) {
                $('#'+this.internal_id+' label').removeClass();
                if (this.disabled) {
                    $('#'+this.internal_id+' label').addClass('badge');
                } else {
                    $('#'+this.internal_id+' label').addClass('btn btn-white btn-round');
                }
                var option = false;
                for (var index in this.options) {
                    if (this.options[index].value == newValue) {
                        option = this.options[index];
                    }
                }
                if (option) {
                    var target = '#'+this.internal_id+' label[for='+this.internal_id+'-'+newValue+']';
                    $(target).removeClass('btn-white');
                    if (this.disabled) {
                        $(target).addClass('badge-'+option.color+' active');
                    } else {
                        $(target).addClass('btn-'+option.color+' active');
                    }
                }
            },

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
