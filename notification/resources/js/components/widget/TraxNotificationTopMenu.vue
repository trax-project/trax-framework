<template>

    <li class="nav-item dropdown" :id="id+'-top-menu'" v-if="notifications">
        <a class="nav-link" href="#"  id="navbarNotifMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
            <span class="notification" v-if="notifications.length">{{ notifications.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarNotifMenu">

            <a class="dropdown-item" href="#" v-for="notification in notifications" :key="notification.id" 
                :data-id="notification.id" @click="open(notification)">
                    {{ notification.notification.title }}
            </a>

            <div class="dropdown-divider" v-if="notifications.length"></div>

            <a class="dropdown-item" :href="app_url">
                <strong>{{ lang.trax_notification.common.display_all_notifications }}</strong>
            </a>
        </div>
    </li>

</template>

<script>
    export default {
    
        props: {
            id: { default: 'trax-notification' },
        },

        data: function() {
            return {
                lang: lang,
                app_url: app_url,
                notifications: data.notifications,
                bus: this.$bus
            }
        },

        methods: {
        
            open(notif) {
                var title = notif.notification.title;
                this.bus.$emit(this.id+'-modal-open', { headerColor: 'danger', icon: 'notifications', title: title, data: notif });
            }
        }

    }
</script>
