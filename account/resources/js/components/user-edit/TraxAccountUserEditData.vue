<template>
    <div>

        <trax-ui-form :id="id" icons="1" :form="form" :form-extend="formExtend" :action="action" :bus="bus">

            <trax-ui-input type="text" icon="person" :placeholder="lang.trax_account.common.username"
                v-model="form.username" v-bind:error="errors.username" :required="!usernameDisabled" :disabled="usernameDisabled" v-if="withUsername">
            </trax-ui-input>

            <trax-ui-input type="email" icon="email" :placeholder="lang.trax_account.common.email"
                v-model="form.email" v-bind:error="errors.email" :required="!emailDisabled" :disabled="emailDisabled">
            </trax-ui-input>

            <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.firstname"
                v-model="form.firstname" v-bind:error="errors.firstname" required="1">
            </trax-ui-input>

            <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_account.common.lastname"
                v-model="form.lastname" v-bind:error="errors.lastname" required="1">
            </trax-ui-input>

            <trax-ui-select icon="flag" v-model="form.lang" :options="lang_select" v-if="lang_select.length > 1">
            </trax-ui-select>

            <slot></slot>

        </trax-ui-form>

    </div>
</template>

<script>
    export default {
    
        props: {
            withUsername: null,
            myProfile: null,
            selfEdit: null,
            formExtend: null
        },
        
        data: function() {
            return {
                loaded: false,
                id: 'trax-account-user-edit',
                lang: lang,
                lang_select: data.lang_select,
                data: {
                    source_code: null
                },
                form: {
                    username: '',
                    firstname: '',
                    lastname: '',
                    email: '',
                    lang: '',
                },
                errors: {},
                action: {
                    align: 'right',
                    label: lang.trax_ui.form.save
                },
                bus: this.$bus
            }
        },

        computed: {

            usernameDisabled() {
                return this.myProfile || this.selfEdit ? true : null;
            },

            emailDisabled() {
                return !this.withUsername && (this.myProfile || this.selfEdit) ? true : null;
            },
        },

        created: function() {
            var that = this;

            this.bus.$on(that.id+'-data', function(data) {
                that.selectData(data);
                that.loaded = true;
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
