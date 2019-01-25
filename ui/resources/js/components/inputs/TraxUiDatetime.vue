<template>

    <span class="bmd-form-group">
        <div class="input-group" v-if="icon">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">{{ icon }}</i>
                </span>
            </div>
            <input :id="id" :class="type+'picker form-control'"
                type="text" 
                :placeholder="placeholder" 
                :required="required" 
                :disabled="disabled" 
                :value="dispValue"
            >
        </div>
        <div class="row" v-else-if="col">
            <label :class="labelClass" style="padding-top:1.7rem;">
                {{ placeholder }}
            </label>
            <div :class="formColClass">
                <input :id="id" :class="type+'picker form-control'"
                    type="text" 
                    :required="required" 
                    :disabled="disabled" 
                    :value="dispValue"
                >
                <span class="trax-form-error" v-if="error"> {{ error }} </span>
            </div>
        </div>
        <div v-else>
            <input :id="id" :class="type+'picker form-control'"
                type="text" 
                :placeholder="placeholder" 
                :required="required" 
                :disabled="disabled" 
                :value="dispValue"
            >
            <span class="trax-form-error" v-if="error"> {{ error }} </span>
        </div>
        <span class="trax-form-error" v-if="error && icon"> {{ error }} </span>
    </span>
    
</template>

<script>
    export default {

        props: {
            icon: null,
            col: null,
            value: null,
            disabled: null,
            placeholder: null,
            required: null,
            error: null,
            type: {default: 'datetime'},
            viewMode: {default: 'days'},
            format: null,
            iso: null
        },

        data() {
            return {
                id: 'trax-ui-datetime-'+this.uuid(), 
                locale: document.documentElement.lang,
                colBreak: null,
                colSize: null
            }
        },

        computed: {

            dispValue: function() {
                if (!this.value) return '';
                var res = this.value;
                if (this.iso) res = moment(res).format('DD MMM YYYY, HH:mm');
                else if (moment(res, 'YYYY-MM-DD HH:mm:ss', true).isValid()) res = moment(res).format('DD MMM YYYY, HH:mm');
                else if (moment(res, 'YYYY-MM-DD', true).isValid()) res = moment(res).format('DD MMM YYYY');
                else if (moment(res, 'HH:mm:ss', true).isValid()) res = moment(res).format('HH:mm');
                return res ? res : '';
            },

            appliedFormat: function () {
                if (this.format) {
                    return this.format;
                } else if (this.type == 'datetime') {
                    return 'DD MMM YYYY, HH:mm';
                } else if (this.type == 'date') {
                    return 'DD MMM YYYY';
                } else if (this.type == 'time') {
                    return 'HH:mm';
                }
            },

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

            $('#'+this.id).on('dp.change', function (e) {
                var value = null;
                if (e.date) value = that.dateValue(e.date);
                that.$emit('input', value);
            });

            $('#'+this.id).on('dp.show', function (e) {
                $(e.target).data("DateTimePicker").viewMode(that.viewMode); 
            });

            $('body').bootstrapMaterialDesign({autofill: false});

            var icons = {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            };

            $('.datetimepicker').datetimepicker({
                locale: that.locale,
                format: that.appliedFormat,
                viewMode: that.viewMode,
                icons: icons
            });

            $('.datepicker').datetimepicker({
                locale: that.locale,
                format: that.appliedFormat,
                viewMode: that.viewMode,
                icons: icons
            });

            $('.timepicker').datetimepicker({
                locale: that.locale,
                format: that.appliedFormat,
                viewMode: that.viewMode,
                icons: icons
            });

        },

        methods: {

            dateValue(date) {
                if (this.iso) {
                    return date.format();
                } else if (this.type == 'datetime') {
                    return date.format('YYYY-MM-DD HH:mm')+':00';
                } else if (this.type == 'date') {
                    return date.format('YYYY-MM-DD');
                } else if (this.type == 'time') {
                    return date.format('HH:mm')+':00';
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
