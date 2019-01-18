<template>
    <trax-ui-ajax-table-with-crud-actions 
        :id="id" :titles="titles" :props="props" :bus="bus" :actions="actions" 
        :endpoint="endpoint" :endpoint-params="endpointParams">
    </trax-ui-ajax-table-with-crud-actions>
</template>

<script>
    export default {
    
        props: ['bus'],

        data: function() {
            return {
                id: 'trax-account-group',
                endpoint: app_url+"trax/ajax/account/groups",
                titles: [
                    lang.trax_ui.form.name,
                    lang.trax_ui.form.description,
                    lang.trax_ui.form.status,
                    ''
                ],
                props: [
                    {source: 'data.name', class: 'font-weight-bold'},
                    {source: 'data.description', width: '50%', truncate: 150},
                    {source: this.status }
                ],
                actions: [
                    {name: 'members', icon: 'group', class: 'btn-default', url: app_url+'trax/ui/account/group/edit'}
                ],
                endpointParams: {
                    with: ['status']
                }
            }
        },

        methods: {
        
            // Columns rendering

            status(data, type, row, meta) {
                var status_code = row.data.status_code;
                var label = row.status.name;
                var color = 'info';
                if (status_code == 'active') color = 'success';
                else if (status_code == 'maintenance') color = 'warning';
                else if (status_code == 'archived') color = 'default';
                return '<span class="badge badge-'+color+'">'+label+'</span>';
            }
        }

    }
</script>
