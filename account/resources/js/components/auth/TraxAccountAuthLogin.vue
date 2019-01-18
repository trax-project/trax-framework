<template>

    <trax-ui-ajax-form id="trax-account-auth-login" :endpoint="endpoint" :form="form" :action="action" 
        :bus="bus" icons="1" toastr-disabled="1" :color="color">

        <trax-ui-input type="text" icon="person" :placeholder="lang.trax_account.common.username"
            v-model="form.username" :error="errors.username" required="1" v-if="withUsername">
        </trax-ui-input>

        <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
            v-model="form.email" :error="errors.email" required="1" v-if="!withUsername">
        </trax-ui-input>

        <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.password"
            v-model="form.password" :error="errors.password" required="1">
        </trax-ui-input>
        
        <trax-ui-checkbox v-if="can_remember" :text="lang.trax_account.common.remember_me" v-model="form.remember">
        </trax-ui-checkbox>

    </trax-ui-ajax-form>

</template>

<script>
    export default {
    
        props: ['email', 'withUsername', 'remember', 'canRemember', 'color'],
        
        data: function() {
            return {
                lang: lang,
                home_url: app_url,
                endpoint: app_url+"login",
                form: {
                    username: '',
                    email: this.email,
                    password: '',
                    remember: this.remember
                },
                errors: {},
                can_remember: this.canRemember,
                action: {
                    align: 'center',
                    label: lang.trax_account.common.log_in
                },
                bus: new Vue()
            }
        },

        created: function() {
            var that = this;

            this.bus.$on('trax-account-auth-login-errors', function(errors) {
                that.errors = errors;
            });

            this.bus.$on('trax-account-auth-login-created', function(request) {
                window.location = that.home_url;
            });
        }
    }
</script>
