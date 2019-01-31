<template>

    <div class="trax-modal-plain-header modal fade" :id="id" tabindex="-1" role="dialog" aria-hidden="true">
        <div :class="'modal-dialog '+sizeClass">
            <div class="modal-content">
                <div class="card card-plain">
                    <div class="modal-header">
                        <div :class="'card-header '+colorClass">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons text-white">clear</i>
                            </button>
                            <i class="material-icons text-white float-left mr-2" v-if="modal_icon">{{ modal_icon }}</i>
                            <h4 class="text-left"> {{ modal_title }} </h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: ['id', 'bus', 'icon', 'title', 'headerColor', 'size'],

        data: function() {
            return {
                modal_icon: this.icon,
                modal_title: this.title,
                modal_header_color: this.headerColor,
            }
        },

        computed: {

            sizeClass: function() {
                return this.size ? 'modal-'+this.size : '';
            },

            colorClass: function() {
                return this.modal_header_color ? 'card-header-'+this.modal_header_color : 'card-header-default';
            }
        },

        created: function() {
            this.bus.$on(this.id+'-open', this.open);
            this.bus.$on(this.id+'-close', this.close);
        },
        
        methods: {
        
            open: function(event) {
                if (event) {
                    if (event.icon) this.modal_icon = event.icon;
                    if (event.title) this.modal_title = event.title;
                    if (event.headerColor) this.modal_header_color = event.headerColor;
                } 
                $('#'+this.id).modal('show');
            },
        
            close: function(event) {
                $('#'+this.id).modal('hide');
            }
        }
    }
</script>
