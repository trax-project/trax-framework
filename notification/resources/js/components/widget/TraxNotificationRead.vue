<template>
    <trax-ui-modal-plain-header :id="id+'-modal'" :bus="bus">

        <div :id="id" v-if="notif">
            
            <p class="font-weight-bold" v-if="notif.notification.context">
                {{ notif.notification.context }}
            </p>

            <p>{{ notif.notification.message }}</p>

            <div class="text-center mt-4 mb-3">

                <!-- Action button -->
                <a :href="notif.notification.data.action.url" class="btn btn-round btn-default" v-if="notif.notification.data.action">
                    {{ notif.notification.actionLabel }}
                </a>

                <!-- Delete button -->
                <button class="btn btn-round btn-danger" @click="deleteNotif">
                    {{ lang.trax_notification.common.delete_message }}
                </button>

            </div>

        </div>

    </trax-ui-modal-plain-header>
</template>

<script>
    export default {
    
        props: {
            id: { default: 'trax-notification' },
        },
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/notification/notification-users",
                notif: null,
                bus: this.$bus
            }
        },

        created: function() {
            this.bus.$on(this.id+'-modal-open', this.opened);
        },

        methods: {
        
            opened: function(event) {
                this.notif = event.data;
                if (this.notif.data.read) return;

                // Record status
                var that = this;
                var data = { id: this.notif.id, read: true };
                axios.put(this.endpoint+'/'+this.notif.id, data)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-notification-read', that.notif.id);
                    })
                    .catch(function (error) {
                    });
            },

            deleteNotif: function(event) {
                var that = this;
                axios.delete(this.endpoint+'/'+that.notif.id)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-modal-close');
                        that.bus.$emit(that.id+'-notification-deleted', that.notif.id);
                    })
                    .catch(function (error) {
                    });
            }

        }

    }
</script>
