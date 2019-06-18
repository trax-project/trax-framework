<template>

    <form autocomplete="off" v-on:submit.prevent="saveData" :class="formClass" v-show="loaded">
        <slot></slot>
        
        <div :class="'trax-form-actions text-'+action.align">
            <button type="submit" class="btn btn-primary btn-round"> {{ action.label }} </button>
        </div>
        
    </form>
    
</template>

<script>
    export default {

        props: { 
            id: null, 
            form: null, 
            formExtend: null,
            icons: { default: false }, 
            action: null, 
            source: {default: '1'},
            target: {default: 'final'},
            bus: null
        },
    
        data: function() {
            return {
                lang: lang,
                loaded: false
            }
        },
        
        computed: {

            formClass: function() {
                return this.icons ? 'trax-form-with-icons' : 'trax-form';
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(this.id+'-'+this.source+'-save-all', this.saveAll);

            this.bus.$on(that.id+'-data', function(data) {
                that.loaded = true;
            });
        },
        
        methods: {

            saveAll(data) {
                this.errors = {};
                for (var attr in this.form) { data[attr] = this.form[attr]; }
                for (var attr in this.formExtend) { data[attr] = this.formExtend[attr]; }
                this.bus.$emit(this.id+'-'+this.target+'-save-all', data);
            },

            saveData() {
                var data = {};
                for (var attr in this.form) { data[attr] = this.form[attr]; }
                for (var attr in this.formExtend) { data[attr] = this.formExtend[attr]; }
                this.bus.$emit(this.id+'-errors', {});
                this.bus.$emit(this.id+'-changed', data);
            }
        }
    }
</script>
