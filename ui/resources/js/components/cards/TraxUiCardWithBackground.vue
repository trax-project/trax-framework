<template>

    <div class="card card-raised card-background mt-2 trax-card-with-background" :style="'background-image: url('+backgroundUrl+')'">
        <div :class="'card-body '+alignClass">
            <div class="w-100">
                <slot></slot>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            verticalAlign: null,
            backgroundFolder: {default: app_url+'img/lib/backgrounds'},
            backgroundImage: {default: 'default.jpg'}
        },

        computed: {

            alignClass: function() {
                if (this.verticalAlign) return 'd-flex align-items-'+this.verticalAlign;
                return '';
            },

            backgroundUrl: function() {
                return this.backgroundFolder + '/' + this.backgroundImage;
            }
        }
    }
</script>
