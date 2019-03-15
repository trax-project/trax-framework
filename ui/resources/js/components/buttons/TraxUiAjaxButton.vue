<template>

    <div>
        <button :class="buttonClass" @click="clicked">{{ label }}</button>
        <trax-ui-modal-confirm :id="internalId" :title="confirmTitle" :bus="internalBus" v-if="toBeConfirmed">{{ confirmText }}</trax-ui-modal-confirm>
        <trax-ui-toastr :id="internalId+'-toastr'" passed-label="1" :bus="internalBus"></trax-ui-toastr>
    </div>
    
</template>

<script>
    export default {
    
        props: {
            label: null,
            color: {default: 'primary'},
            endpoint: null,
            confirm: null,
            confirmTitle: {default: lang.trax_ui.form.confirmation},
            confirmText: {default: lang.trax_ui.form.confirmation_q},
            id: null,
            bus: null
        },
        
        data: function() {
            return {
                lang: lang,
                internalBus: new Vue()
            }
        },
        
        computed: {

            internalId: function() {
                return 'trax-ui-ajax-button-'+this.uuid();
            },

            toBeConfirmed: function() {
                return this.confirm || this.confirmTitle || this.confirmText;
            },

            buttonClass: function() {
                return 'btn btn-round btn-'+this.color;
            }

        },
        
        created: function() {
            this.internalBus.$on(this.internalId+'-confirmed', this.confirmed);
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
                if (this.toBeConfirmed) this.internalBus.$emit(this.internalId+'-open');
                else this.confirmed();
            },

            confirmed() {
                this.request();
            },

            request() {
                var that = this;
                axios.post(that.endpoint)
                    .then(function (response) {
                        if (that.bus && that.id) that.bus.$emit(that.id+'-success');
                        that.internalBus.$emit(that.internalId+'-toastr-success', that.lang.trax_ui.form.done);
                    })
                    .catch(function (error) {
                        that.internalBus.$emit(that.internalId+'-toastr-error');
                    });
            }
        }
    }
</script>
