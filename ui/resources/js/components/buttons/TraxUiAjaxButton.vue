<template>

    <div>
        <button class="btn btn-primary btn-round" @click="clicked">{{ label }}</button>
        <trax-ui-modal-confirm :id="id" :title="confirmTitle" :bus="bus" v-if="toBeConfirmed">{{ confirmText }}</trax-ui-modal-confirm>
        <trax-ui-toastr :id="id+'-toastr'" passed-label="1" :bus="bus"></trax-ui-toastr>
    </div>
    
</template>

<script>
    export default {
    
        props: {
            label: null,
            endpoint: null,
            confirm: null,
            confirmTitle: {default: lang.trax_ui.form.confirmation},
            confirmText: {default: lang.trax_ui.form.confirmation_q}
        },
        
        data: function() {
            return {
                lang: lang,
                bus: new Vue()
            }
        },
        
        computed: {

            id: function() {
                return 'trax-ui-ajax-button-'+this.uuid();
            },

            toBeConfirmed: function() {
                return this.confirm || this.confirmTitle || this.confirmText;
            }

        },
        
        created: function() {
            this.bus.$on(this.id+'-confirmed', this.confirmed);
        },
        
        methods: {

            uuid() {
                var res = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                    return v.toString(16);
                });
                return res;
            },

            clicked() {
                if (this.toBeConfirmed) this.bus.$emit(this.id+'-open');
                else this.confirmed();
            },

            confirmed() {
                this.request();
            },

            request() {
                var that = this;
                axios.post(that.endpoint)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-toastr-success', that.lang.trax_ui.form.done);
                    })
                    .catch(function (error) {
                        that.bus.$emit(that.id+'-toastr-error');
                    });
            }
        }
    }
</script>
