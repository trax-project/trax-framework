<template>

    <trax-ui-ajax-data-table 
        :id="id+'-table'" :props="props" :endpoint="endpoint" :endpoint-params="endpointParams"
        :header="header" :header-color="headerColor" :paging="paging" :searching="searching" :bus="bus"
        :empty-message="lang.trax_notification.common.no_notification">
    </trax-ui-ajax-data-table>

</template>

<script>
    export default {
    
        props: {
            id: { default: 'trax-notification' },
            header: { default: 1 },
            headerColor: { default: 'default' },
            paging: { default: 1 },
            searching: { default: 1 },
        },

        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/notification/notification-users",
                endpointParams: {
                    with: ['notification'],
                    'order-by': 'id',
                    'order-dir': 'desc'
                },
                props: [
                    {source: this.preview, orderable: false },
                ],
                notifications: {},
                bus: this.$bus
            }
        },

        created: function() {
            this.bus.$on(this.id+'-notification-deleted', this.deleted);
            this.bus.$on(this.id+'-notification-read', this.read);
        },

        mounted: function() {
            var that = this;

            $('#'+this.id+'-table-table tbody').on( 'click', '.notification-link', function(event) {
                that.open($(this).attr('data-id'));
            });

        },

        methods: {
        
            preview(data, type, row, meta) {
                this.notifications[row.id] = row;
                var title = row.notification.title;
                var res = '';
                if (!row.data.read) res += '<span class="badge badge-danger float-right ml-1">'+this.lang.trax_notification.common.new+'</span>';
                res += '<span class="font-weight-bold">'+title+'<span>';
                res += '<br><small class="font-weight-light">'+moment(row.notification.created_at).format('DD MMM YYYY')+'</small>';
                return '<div class="notification-link" style="cursor:pointer" data-id="'+row.id+'">'+res+'</div>';
            },

            open(id) {
                var notif = this.notifications[id];
                var title = notif.notification.title;
                this.bus.$emit(this.id+'-modal-open', { headerColor: 'danger', icon: 'notifications', title: title, data: notif });
            },

            deleted(id) {
                this.bus.$emit(this.id+'-table-refresh', id);
            },

            read(id) {
                $('#'+this.id+'-table-table tbody .notification-link[data-id='+id+'] .badge').hide();
            }
        }

    }
</script>
