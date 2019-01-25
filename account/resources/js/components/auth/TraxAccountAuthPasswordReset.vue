<template>

    <trax-ui-ajax-form id="trax-account-auth-password-reset" :endpoint="endpoint" :form="form" 
        :action="action" :bus="bus" icons="1" :color="color" toastr-passed-label="1" 
        toastr-form-error-disabled="1" toastr-form-success-disabled="1">

        <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
            v-model="form.email" v-bind:error="errors.email" required="1">
        </trax-ui-input>

        <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.password"
            v-model="form.password" v-bind:error="errors.password" required="1">
        </trax-ui-input>

        <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.password_confirmation"
            v-model="form.password_confirmation" v-bind:error="errors.password_confirmation" required="1">
        </trax-ui-input>

    </trax-ui-ajax-form>

</template>

<script>
    export default {
    
        props: ['email', 'token', 'color'],
        
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"password/reset",
                home_url: app_url+"home",
                form: {
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: ''
                },
                errors: {},
                action: {
                    align: 'center',
                    label: lang.trax_ui.form.save
                },
                bus: new Vue()
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on('trax-account-auth-password-reset-errors', function(errors) {
                that.errors = errors;
            });

            this.bus.$on('trax-account-auth-password-reset-created', function(request) {
                window.location = that.home_url;
            });
        }
    }
</script>
