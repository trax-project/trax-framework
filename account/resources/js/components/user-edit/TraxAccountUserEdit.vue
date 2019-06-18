<template>

    <trax-ui-ajax-form-hub id="trax-account-user-edit" :endpoint="endpoint" 
         :resource-id="userid" :props="props" :forget="forget" :bus="bus" :toastr-passed-label="1">
    </trax-ui-ajax-form-hub>

</template>

<script>
    export default {
    
        props: ['userid'],
        
        data: function() {
            return {
                endpoint: app_url+"trax/ajax/account/users",
                props: {
                    username: { source:'username' },
                    email: { source:'email' },
                    lang: { source:'data.lang' },
                    firstname: { source:'data.firstname' },
                    lastname: { source:'data.lastname' },
                    source_code: { source:'source_code' },
                    admin: { source:'admin' },
                    active: { source:'active' },
                    status: { source:'data.status' },
                    entity_type_code: { source:'entity_type_code' },
                    role_id: { source:'role_id' },
                    organization_id: { source:'organization_id' },
                    entity_id: { source:'entity_id' },
                    rights_level_code: { source:'data.rights_level_code' },
                    user_function_code: { source:'data.user_function_code' },
                    picture: { source:'data.picture' }
                },
                forget: [
                    'password',
                    'password_confirmation'
                ],
                bus: this.$bus
            }
        }
    }
</script>
