<template>
</template>

<script>
    export default {

        props: { 
            id: null, 
            options: null,
            labels: null,
            passedLabel: null,
            noProgress: null,
            bus: null
        },

        data: function() {
            return {
                defaultOptions: {
                    progressBar: true, 
                    timeOut: "2000"
                },
                noProgressOptions: {
                    closeButton: true,
                },
                defaultLabels: {
                    success: lang.trax_ui.form.saved,
                    error: lang.trax_ui.form.error
                },
                finalOptions: null,
                finalLabels: null
            }
        },
        
        created: function() {
            var that = this;

            // Init
            var defaultOptions = this.noProgress ? this.noProgressOptions : this.defaultOptions;
            this.finalOptions = this.options ? this.options : defaultOptions;
            this.finalLabels = this.labels ? this.labels : this.defaultLabels;

            this.bus.$on(that.id+'-success', function(label) {
                var message = that.passedLabel && label != undefined ? label : that.finalLabels.success;
                window.toastr.success(message);
            });

            this.bus.$on(that.id+'-error', function(label) {
                var message =  that.passedLabel && label != undefined ? label : that.finalLabels.error;
                window.toastr.error(message);
            });

        },
        
        mounted: function() {
            window.toastr.options = this.finalOptions;
        }
    }
</script>
