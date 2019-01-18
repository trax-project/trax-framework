<template>

    <trax-ui-ajax-form-in-modal :id="id" :endpoint="endpoint" 
        :props="props" :titles="titles" :form="form" :bus="bus" icons="1">

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.name"
            v-model="form.name" :error="errors.name" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.username"
            v-model="form.username" :error="errors.username" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.password"
            v-model="form.password" :error="errors.password" required="1">
        </trax-ui-input>

    </trax-ui-ajax-form-in-modal>
    
</template>

<script>
    export default {

        props: ['bus'],
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/basic-clients",
                id: 'trax-account-basic-client-edit',
                form: {},
                errors: {},
                props: {
                    id: { source:'id' },
                    name: { source:'data.name' },
                    username: { source:'username', default:this.uuid },
                    password: { source:'password', default:this.uuid }
                },
                titles: {
                    new: lang.trax_account.common.new_client,
                    update: lang.trax_account.common.client_update
                }
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(that.id+'-data', function(data) {
                that.form = data;
            });

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });
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
