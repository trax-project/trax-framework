<template>
    <div>

        <trax-ui-form :id="id" icons="1" :form="form" :action="action" :bus="bus" v-show="internal">

            <trax-ui-input type="password" icon="lock" :placeholder="lang.trax_account.common.current_password"
                v-model="form.current_password" v-bind:error="errors.current_password" required="1">
            </trax-ui-input>

            <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.new_password"
                v-model="form.password" v-bind:error="errors.password" required="1">
            </trax-ui-input>

            <trax-ui-input type="password" icon="lock_outline" :placeholder="lang.trax_account.common.new_password_confirmation"
                v-model="form.password_confirmation" v-bind:error="errors.password_confirmation" required="1">
            </trax-ui-input>

        </trax-ui-form>

    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                id: 'trax-account-user-edit',
                lang: lang,
                data: {
                    source_code: null
                },
                form: {
                    current_password: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: {},
                action: {
                    align: 'right',
                    label: lang.trax_account.common.change_password
                },
                bus: this.$bus
            }
        },

        computed: {

            internal: function () {
                return this.data.source_code == 'internal';
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on(that.id+'-data', function(data) {
                that.selectData(data);
            });

            this.bus.$on(that.id+'-updated', function(request) {
                that.form.current_password = '';
                that.form.password = '';
                that.form.password_confirmation = '';
            });

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });
        },

        methods: {

            selectData(data) {
                this.data = data;
                for (var index in this.form) {
                    this.form[index] = data[index];
                }
            }
        }
    }
</script>
