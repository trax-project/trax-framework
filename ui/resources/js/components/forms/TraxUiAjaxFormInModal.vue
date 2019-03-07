<template>

    <form autocomplete="off" v-on:submit.prevent="saveData" :class="formClass">
        <slot></slot>
        
        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" data-dismiss="modal"> {{ lang.trax_ui.form.cancel }} </button>
            <button type="submit" class="btn btn-primary btn-round"> {{ actionLabel }} </button>
        </div>
    </form>
    
</template>

<script>
    export default {

        props: { 
            id: null, 
            toastrId: null, 
            endpoint: null, 
            icons: null, 
            props: null, 
            titles: null, 
            form: null, 
            action: null,
            bus: null,
        },
    
        data: function() {
            return {
                lang: lang,
                data: this.form,
                duplicate: false,
                nativeData: {},
            }
        },
        
        computed: {

            actionLabel: function() {
                return this.action ? this.action.label : this.lang.trax_ui.form.save;
            },

            formClass: function() {
                return this.icons ? 'trax-form-with-icons' : 'trax-form';
            }
        },

        watch: {
            form: function (data) {
                this.data = data;
            }
        },

        created: function() {
            this.bus.$on(this.id+'-create', this.create);
            this.bus.$on(this.id+'-update', this.update);
            this.bus.$on(this.id+'-duplicate', this.duplicateForm);
            this.bus.$on(this.id+'-send', this.send);
        },
        
        methods: {

            create(defaultData) {
                this.clear(defaultData);
                this.bus.$emit(this.id+'-data', this.data, this.nativeData);
                this.bus.$emit(this.id+'-open', {title: this.titles.new});
                this.duplicate = false;
            },
            
            update(data) {
                this.clear();
                this.setData(data);
                this.bus.$emit(this.id+'-data', this.data, this.nativeData);
                this.bus.$emit(this.id+'-open', {title: this.titles.update});
                this.duplicate = false;
            },
            
            duplicateForm(data) {
                this.clear();
                this.setData(data);
                this.bus.$emit(this.id+'-data-duplicate', this.data, this.nativeData);
                this.bus.$emit(this.id+'-open', {title: this.titles.duplicate});
                this.duplicate = true;
            },
            
            send(data) {
                this.setData(data);
                this.saveData();
            },
            
            clear(defaultData) {
                this.nativeData = {};
                this.setDefaultData(defaultData);
                this.bus.$emit(this.id+'-errors', {});
            },
        
            setDefaultData(defaultData) {
                var data = defaultData == undefined ? {} : defaultData;
                for (var index in this.props) {
                    var prop = this.props[index];
                    if (prop.default !== undefined) {
                        if (typeof prop.default === "function") {
                            data[index] = prop.default.call(this);
                        } else {
                            data[index] = prop.default;
                        }
                    } else if (data[index] == undefined) {
                        data[index] = undefined;
                    }
                }
                this.data = data;
            },
            
            setData(data) {
                this.nativeData = data;
                for (var index in this.props) {
                    var prop = this.props[index];
                    if (prop.value !== undefined) {
                        this.data[index] = prop.value;
                    } else {
                        if (typeof prop.source === "function") {
                            this.data[index] = prop.source.call(this, data);
                        } else {
                            this.data[index] = this.resolveProp(prop.source, data);
                        }
                    }
                }
            },
            
            saveData() {
                this.bus.$emit(this.id+'-errors', {});
                if (this.data.id && !this.duplicate) this.putData(this.data.id, this.data);
                else this.postData(this.data);
            },
            
            putData(id, data) {
                var that = this;
                var outputData = this.outputData(data);
                var endpoint = this.endpoint+'/'+id;
                that.$bus.$emit('loader-start-loading');
                axios.put(endpoint, outputData)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-close');
                        that.bus.$emit(that.id+'-updated', {data: outputData, response: response});
                        that.$bus.$emit('loader-stop-loading');
                    })
                    .catch(function (error) {
                        that.processErrors(error.response.data);
                        that.$bus.$emit('loader-stop-loading');
                    });
            },
            
            postData(data) {
                var that = this;
                var outputData = this.outputData(data);
                var endpoint = this.endpoint;
                if (this.duplicate) endpoint += '/'+data.id+'/duplicate';
                that.$bus.$emit('loader-start-loading');
                axios.post(endpoint, outputData)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-close');
                        that.bus.$emit(that.id+'-created', {data: outputData, response: response});
                        that.$bus.$emit('loader-stop-loading');
                    })
                    .catch(function (error) {
                        that.processErrors(error.response.data);
                        that.$bus.$emit('loader-stop-loading');
                    });
            },

            outputData(data) {
                var res = {};
                for (var prop in data) {
                    var val = data[prop];
                    if (this.props[prop] && this.props[prop].output) {
                        res[prop] = this.props[prop].output.call(this, val);
                    } else {
                        res[prop] = val;
                    }
                }
                if (this.duplicate) delete res.id;
                return res;
            },
            
            resolveProp(path, obj) {
                return path.split('.').reduce(function(prev, curr) {
                    return prev ? prev[curr] : null
                }, obj)
            },

            processErrors(data) {
                if (typeof data == 'string') {
                    if (this.toastrId) this.bus.$emit(this.toastrId+'-error', data);
                    return;
                } 
                var errors = data.errors;
                for (var name in errors) {
                    var error = errors[name];
                    if (error instanceof Array) errors[name] = error[0];
                }
                this.bus.$emit(this.id+'-errors', errors);
            }
        }
    }
</script>
