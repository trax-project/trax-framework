<template>

    <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-hidden="true">
        <div :class="'modal-dialog '+sizeClass">
            <div class="modal-content">
                <div class="modal-header" v-if="!noHeader">
                    <h4 class="modal-title text-left"> {{ modal_title }} </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">clear</i>
                    </button>
                </div>
                <div :class="bodyClass">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: ['id', 'title', 'bus', 'size', 'noHeader', 'noTitle'],

        data: function() {
            return {
                modal_title: this.title
            }
        },

        computed: {

            sizeClass: function() {
                return this.size ? 'modal-'+this.size : '';
            },

            bodyClass: function() {
                return this.noTitle ? 'modal-body pt-0' : 'modal-body';
            }
        },

        created: function() {
            this.bus.$on(this.id+'-open', this.open);
            this.bus.$on(this.id+'-close', this.close);
        },
        
        methods: {
        
            open: function(event) {
                if (event && event.title) this.modal_title = event.title;
                $('#'+this.id).modal('show');
            },
        
            close: function(event) {
                $('#'+this.id).modal('hide');
            }
        }
    }
</script>
