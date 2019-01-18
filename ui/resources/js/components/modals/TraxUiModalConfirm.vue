<template>

    <trax-ui-modal-default :id="id" :title="title" :bus="bus">
        <p>
            <slot></slot>
        </p>
        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" data-dismiss="modal"> {{ lang.trax_ui.form.cancel }} </button>
            <button type="button" class="btn btn-danger btn-round" data-dismiss="modal" @click="confirm"> {{ lang.trax_ui.form.confirm }} </button>
        </div>
    </trax-ui-modal-default>
    
</template>

<script>
    export default {

        props: ['title', 'bus', 'id'],

        data: function() {
            return {
                lang: lang,
                data: null
            }
        },

        created: function() {
            this.bus.$on(this.id+'-open', this.open);
        },
        
        methods: {
        
            open: function(event) {
                if (event && event.data) this.data = event.data;
            },
        
            confirm: function(event) {
                this.bus.$emit(this.id+'-confirmed', this.data)
            }
        }
    }
</script>
