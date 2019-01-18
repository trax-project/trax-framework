<template>

    <trax-ui-form icons="1" :id="id" :form="form" :action="action" :bus="bus">

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.name"
            v-model="form.name" v-bind:error="errors.name" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" rows="3" :placeholder="lang.trax_ui.form.description"
            v-model="form.description" v-bind:error="errors.description">
        </trax-ui-input>

    </trax-ui-form>

</template>

<script>
    export default {
    
        data: function() {
            return {
                id: 'trax-account-role-edit',
                lang: lang,
                data: {},
                form: {
                    name: '',
                    description: ''
                },
                errors: {},
                action: {
                    align: 'right',
                    label: lang.trax_ui.form.save
                },
                bus: this.$bus
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(that.id+'-data', function(data) {
                that.selectData(data);
            });

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });
        },

        methods: {

            selectData(data) {
                this.data = data;
                for (var index in this.form) {
                    this.form[index] = data[index];
                }
            }
        }
    }
</script>
