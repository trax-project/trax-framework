<template>

    <trax-ui-ajax-form id="trax-account-auth-register" :endpoint="endpoint" :form="form" :action="action" 
        :bus="bus" icons="1" toastr-disabled="1" :color="color">
    
        <trax-ui-input type="text" icon="person" :placeholder="lang.trax_account.common.username"
            v-model="form.username" :error="errors.username" required="1" v-if="withUsername">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.firstname"
            v-model="form.firstname" :error="errors.firstname" required="1">
        </trax-ui-input>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.lastname"
            v-model="form.lastname" :error="errors.lastname" required="1">
        </trax-ui-input>

        <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
            v-model="form.email" :error="errors.email" required="1">
        </trax-ui-input>

        <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.password"
            v-model="form.password" :error="errors.password" required="1">
        </trax-ui-input>

        <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.password_confirmation"
            v-model="form.password_confirmation" :error="errors.password_confirmation" required="1">
        </trax-ui-input>
        
    </trax-ui-ajax-form>

</template>

<script>
    export default {
    
        props: ['withUsername', 'firstname', 'lastname', 'email', 'color'],
        
        data: function() {
            return {
                lang: lang,
                home_url: app_url,
                endpoint: app_url+"register",
                form: {
                    username: '',
                    email: this.email,
                    firstname: this.firstname,
                    lastname: this.lastname,
                    lang: document.documentElement.lang,
                    password: '',
                    password_confirmation: ''
                },
                errors: {},
                action: {
                    align: 'center',
                    label: lang.trax_account.common.register
                },
                bus: new Vue()
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on('trax-account-auth-register-errors', function(errors) {
                that.errors = errors;
            });

            this.bus.$on('trax-account-auth-register-created', function(request) {
                window.location = that.home_url;
            });
        }
    }
</script>
