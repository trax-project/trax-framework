<template>

    <form autocomplete="off" v-on:submit.prevent="saveData" :class="formClass" v-show="loaded">
        <slot></slot>
        
        <div :class="'trax-form-actions text-'+action.align">
            <button type="submit" :class="'btn btn-round btn-'+color"> {{ action.label }} </button>
        </div>

        <trax-ui-toastr :id="id+'-toastr'" :options="toastrOptions" :labels="toastrLabels" :passed-label="toastrPassedLabel" 
            :bus="bus" v-if="!toastrDisabled">
        </trax-ui-toastr>
    </form>
    
</template>

<script>
    export default {

        props: { 
            id: null, 
            endpoint: null, 
            resourceId: null, 
            props: null, 
            form: null, 
            icons: null, 
            action: null, 
            color: {default: 'primary'}, 
            toastrDisabled: null,
            toastrOptions: null,
            toastrLabels: null,
            toastrPassedLabel: null,
            toastrFormErrorDisabled: null,
            toastrFormSuccessDisabled: null,
            bus: null
        },
    
        data: function() {
            return {
                lang: lang,
                data: this.form,
                loaded: false
            }
        },
        
        computed: {

            formClass: function() {
                return this.icons ? 'trax-form-with-icons' : 'trax-form';
            }
        },

        watch: {
            form: function(data) {
                this.data = data;
            }
        },

        created: function() {
            if (this.resourceId) this.getData(this.resourceId);
            else this.loaded = true;
        },
        
        methods: {

            getData(id) {
                var that = this;
                axios.get(this.endpoint+'/'+id)
                    .then(function (response) {
                        that.mapData(response.data);
                        that.loaded = true;
                    })
            },
            
            putData(id, data) {
                var that = this;
                axios.put(this.endpoint+'/'+id, data)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-updated', {data: data, response: response});
                        if (!that.toastrFormSuccessDisabled) {
                            that.bus.$emit(that.id+'-toastr-success', response.data);
                        }
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
            
            postData(data) {
                var that = this;
                axios.post(this.endpoint, data)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-created', {data: data, response: response});
                        if (!that.toastrFormSuccessDisabled) {
                            that.bus.$emit(that.id+'-toastr-success', response.data);
                        }
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

            saveData() {
                this.bus.$emit(this.id+'-errors', {});
                if (this.resourceId || this.data.id != undefined) this.putData(this.resourceId, this.data);
                else this.postData(this.data);
            },
            
            mapData(data) {
                for (var index in this.props) {
                    var prop = this.props[index];
                    this.data[index] = this.resolveProp(prop.source, data);
                }
                this.bus.$emit(this.id+'-data', this.data);
            },
            
            resolveProp(path, obj) {
                return path.split('.').reduce(function(prev, curr) {
                    return prev ? prev[curr] : null
                }, obj)
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
