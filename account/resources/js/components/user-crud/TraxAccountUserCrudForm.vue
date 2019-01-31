<template>

    <trax-ui-ajax-form-in-modal :id="id" :endpoint="endpoint" 
        :props="props" :titles="titles" :bus="bus" :form="form" icons="1">

        <trax-ui-input type="text" icon="person" :placeholder="lang.trax_account.common.username"
            v-model="form.username" :error="errors.username" required="1" :disabled="!internal" v-if="withUsername">
        </trax-ui-input>

        <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
            v-model="form.email" :error="errors.email" required="1" :disabled="!internal">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.firstname"
            v-model="form.firstname" :error="errors.firstname" required="1" :disabled="!internal">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.lastname"
            v-model="form.lastname" :error="errors.lastname" required="1" :disabled="!internal">
        </trax-ui-input>

        <trax-ui-select icon="dns" v-model="form.source_code" :options="user_sources_select" disabled="1" v-show="!internal">
        </trax-ui-select>

    </trax-ui-ajax-form-in-modal>
    
</template>

<script>
    export default {

        props: ['bus', 'withUsername'],
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/users",
                id: 'trax-account-user-edit',
                form: {},
                errors: {},
                user_sources_select: data.user_sources_select,
                props: {
                    id: { source:'id' },
                    username: { source:'username' },
                    email: { source:'email' },
                    firstname: { source:'data.firstname' },
                    lastname: { source:'data.lastname' },
                    source_code: { source:'source_code', default:'internal' }
                },
                titles: {
                    new: lang.trax_account.common.new_user,
                    update: lang.trax_account.common.user_update
                }
            }
        },

        computed: {

            internal: function () {
                return this.form.source_code == 'internal';
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
