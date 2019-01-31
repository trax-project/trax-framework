<template>

    <trax-ui-ajax-table-with-crud-actions 
        :id="id" :titles="titles" :props="props" :bus="bus" 
        :endpoint="endpoint" :edit-url="edit_url">
    </trax-ui-ajax-table-with-crud-actions>
    
</template>

<script>
    export default {
    
        props: ['bus'],

        data: function() {
            return {
                endpoint: app_url+"trax/ajax/account/roles",
                edit_url: app_url+"trax/ui/account/role/edit",
                titles: [
                    lang.trax_ui.form.name,
                    lang.trax_ui.form.description,
                    ''
                ],
                props: [
                    {source: 'data.name', class: 'font-weight-bold'},
                    {source: 'data.description', width: '75%', truncate: 150}
                ],
                id: 'trax-account-role'
            }
        }
    }
</script>
