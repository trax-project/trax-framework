<template>

    <trax-ui-ajax-table-with-crud-actions id="trax-account-basic-client" 
        :titles="titles" :props="props" :endpoint="endpoint" :bus="bus">
    </trax-ui-ajax-table-with-crud-actions>
    
</template>

<script>
    export default {
    
        props: ['bus'],

        data: function() {
            return {
                endpoint: app_url+"trax/ajax/account/basic-clients",
                titles: [
                    lang.trax_ui.form.name,
                    lang.trax_account.common.username,
                    lang.trax_account.common.password,
                    ''
                ],
                props: [
                    {source: 'data.name'},
                    {source: 'username'},
                    {source: 'password'}
                ]
            }
        }
    }
</script>
