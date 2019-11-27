<template>

    <trax-ui-ajax-table-with-crud-actions 
        :id="id" :titles="titles" :props="props" :bus="bus" 
        :endpoint="endpoint" :endpoint-params="endpointParams" 
        :edit-url="edit_url">
    </trax-ui-ajax-table-with-crud-actions>
    
</template>

<script>
    export default {
    
        props: ['bus', 'withUsername'],

        data() {
            var titles = [
                lang.trax_account.common.email,
                lang.trax_account.common.fullname,
                lang.trax_account.common.role,
                ''
            ];
            var props = [
                {source: this.email, order: 'email'},
                {source: this.fullname, order: 'lastname'},
                {source: this.role, order: 'role.data.name' }
            ];
            if (this.withUsername) {
                titles.unshift(lang.trax_account.common.username);
                props.unshift({source: this.username, order: 'username', class: 'font-weight-bold'});
            }
            return {
                endpoint: app_url+"trax/ajax/account/users",
                endpointParams: {
                    with: ['role']
                },
                edit_url: app_url+"trax/ui/account/user/edit",
                titles: titles,
                props: props,
                id: 'trax-account-user'
            }
        },

        methods: {
        
            // Columns rendering

            email(data, type, row, meta) {
                return this.active(row, row.email);
            },

            fullname(data, type, row, meta) {
                return this.active(row, row.data.lastname + ' ' + row.data.firstname);
            },

            username(data, type, row, meta) {
                return this.active(row, row.username);
            },

            role(data, type, row, meta) {
                if (!row.role) return '';
                return this.active(row, row.role.data.name);
            },

            active(row, content) {
                if (row.active) return content;
                return '<span class="trax-text-muted">' + content + '</span>';
            }
        }

    }
</script>
