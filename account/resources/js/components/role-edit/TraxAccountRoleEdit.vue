<template>

    <trax-ui-ajax-form-hub id="trax-account-role-edit" :endpoint="endpoint" 
         :resource-id="roleid" :props="props" :bus="bus">
    </trax-ui-ajax-form-hub>

</template>

<script>
    export default {
    
        props: ['roleid'],
        
        data: function() {
            return {
                endpoint: app_url+"trax/ajax/account/roles",
                props: {
                    name: { source:'data.name' },
                    description: { source:'data.description' },
                    permissions: { source:'data.permissions' }
                },
                bus: this.$bus
            }
        }
    }
</script>
