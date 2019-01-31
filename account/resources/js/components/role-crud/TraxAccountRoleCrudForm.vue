<template>

    <trax-ui-ajax-form-in-modal icons="1" :id="id" :endpoint="endpoint" 
        :props="props" :titles="titles" :bus="bus" :form="form">

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.name"
            v-model="form.name" :error="errors.name" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" rows="3" :placeholder="lang.trax_ui.form.description"
            v-model="form.description" :error="errors.description">
        </trax-ui-input>

    </trax-ui-ajax-form-in-modal>
    
</template>

<script>
    export default {

        props: ['bus'],
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/roles",
                id: 'trax-account-role-edit',
                form: {},
                errors: {},
                props: {
                    id: { source:'id' },
                    name: { source:'data.name' },
                    description: { source:'data.description' }
                },
                titles: {
                    new: lang.trax_account.common.new_role,
                    update: lang.trax_account.common.role_update
                }
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });

            this.bus.$on(that.id+'-data', function(data) {
                that.form = data;
            });
        }

    }
</script>
