<template>

    <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round" @click="toggle">
        <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
        <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
    </button>
    
</template>

<script>
    export default {
    
        data: function() {
            return {
                endpoint: app_url+'trax/ajax/account/users/preferences'
            }
        },

        mounted: function() {
            window.md.misc.sidebar_mini_active = $('body').hasClass('sidebar-mini');
        },
        
        methods: {
        
            toggle: function() {
                var minimize = !$('body').hasClass('sidebar-mini');
                axios.post(this.endpoint, {minisidebar: minimize});
            }
        }
    }
</script>
