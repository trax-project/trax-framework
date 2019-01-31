<template>

    <trax-ui-toastr :id="id+'-toastr'" :options="toastrOptions" :labels="toastrLabels" :passed-label="toastrPassedLabel" 
        :bus="bus" v-if="!toastrDisabled">
    </trax-ui-toastr>

</template>

<script>
    export default {

        props: { 
            id: null, 
            endpoint: null, 
            resourceId: null, 
            props: null, 
            forget: null,
            toastrDisabled: null,
            toastrOptions: null,
            toastrLabels: null,
            toastrPassedLabel: null,
            toastrFormErrorDisabled: null,
            bus: null
        },

        data: function() {
            return {
                lang: lang,
                data: {}
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on(that.id+'-changed', function(data) {
                that.concatData(data);
            });

            this.getData(this.resourceId);
        },
        
        methods: {

            getData(id) {
                var that = this;
                axios.get(this.endpoint+'/'+id)
                    .then(function (response) {
                        that.mapData(response.data);
                    })
                    .catch(function (error) {
                    });
            },
            
            putData(id, data) {
                var that = this;
                axios.put(this.endpoint+'/'+id, data)
                    .then(function (response) {
                        that.forgetData();
                        that.bus.$emit(that.id+'-updated', {data: data, response: response});
                        that.bus.$emit(that.id+'-toastr-success', that.lang.trax_ui.form.saved);
                    })
                    .catch(function (error) {
                        if (error.response.data.errors) {
                            that.processErrors(error.response.data.errors);
                            if (!that.toastrFormErrorDisabled) {
                                that.bus.$emit(that.id+'-toastr-error', that.lang.trax_ui.form.form_error);
                            }
                        } else {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        }
                    });
            },

            mapData(data) {
                for (var index in this.props) {
                    var prop = this.props[index];
                    this.data[index] = this.resolveProp(prop.source, data);
                }
                this.data.id = this.resourceId;
                this.bus.$emit(this.id+'-data', this.data);
            },
            
            resolveProp(path, obj) {
                return path.split('.').reduce(function(prev, curr) {
                    return prev ? prev[curr] : null
                }, obj)
            },

            concatData(data) {
                for (var index in data) {
                    this.data[index] = data[index];
                }
                this.putData(this.resourceId, this.data);
            },
            
            forgetData() {
                for (var index in this.forget) {
                    var prop = this.forget[index];
                    delete this.data[prop];
                }
            },
            
            processErrors(errors) {
                for (var name in errors) {
                    var error = errors[name];
                    if (error instanceof Array) errors[name] = error[0];
                }
                this.bus.$emit(this.id+'-errors', errors);
            }
        }
    }
</script>
