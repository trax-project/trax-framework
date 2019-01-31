<template>

    <trax-ui-ajax-form-in-modal :id="id" :toastr-id="toastrId" :endpoint="endpoint" 
        :props="props" :titles="titles" :bus="bus" :form="form" icons="1">

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.name"
            v-model="form.name" :error="errors.name" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" rows="3" :placeholder="lang.trax_ui.form.description"
            v-model="form.description" :error="errors.description">
        </trax-ui-input>

        <trax-ui-select icon="cached" v-model="form.status_code" :options="status_select">
        </trax-ui-select>

    </trax-ui-ajax-form-in-modal>
    
</template>

<script>
    export default {

        props: ['bus'],
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/groups",
                id: 'trax-account-group-edit',
                toastrId: 'trax-account-group-toastr',
                form: {},
                errors: {},
                status_select: data.status_select,
                props: {
                    id: { source:'id' },
                    name: { source:'data.name' },
                    description: { source:'data.description' },
                    status_code: { source:'data.status_code', default:'draft' }
                },
                titles: {
                    new: lang.trax_account.common.new_group,
                    update: lang.trax_account.common.group_update
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
