<template>

    <trax-ui-ajax-form id="trax-account-auth-password-email" :endpoint="endpoint" :color="color" 
        :form="form" :toastr-options="toastrOptions" :toastr-passed-label="1" :action="action" :bus="bus" icons="1">

        <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
            v-model="form.email" v-bind:error="errors.email" required="1">
        </trax-ui-input>

    </trax-ui-ajax-form>

</template>

<script>
    export default {
    
        props: ['email', 'color'],
        
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"password/email",
                form: {
                    email: this.email
                },
                errors: {},
                action: {
                    align: 'center',
                    label: lang.trax_account.common.reset_password
                },
                toastrOptions: {
                    closeButton: true,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    closeDuration: 200
                },
                bus: new Vue()
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on('trax-account-auth-password-email-errors', function(errors) {
                that.errors = errors;
            });
        }
    }
</script>
