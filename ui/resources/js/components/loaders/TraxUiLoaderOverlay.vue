<template>

    <div class="overlay" v-show="visible">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    
</template>

<script>
    export default {
    
        data: function() {
            return {
                visible: false,
                timer: null,
                bus: this.$bus
            }
        },

        created: function() {
            this.bus.$on('loader-start-loading', this.start);
            this.bus.$on('loader-stop-loading', this.stop);
        },

        methods: {

            start() {
                var that = this;
                this.timer = setTimeout(function() {
                    that.visible = true;
                }, 250);
            },

            stop() {
                clearTimeout(this.timer); 
                this.visible = false;
            }
        }
    }
</script>
