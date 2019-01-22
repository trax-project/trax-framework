<template>
    <div>

        <trax-ui-form :id="id" :form="form" :action="action" :bus="bus">

            <trax-ui-toggle :text="lang.trax_account.common.admin_account" :disabled="selfEdit==1" v-model="form.admin">
            </trax-ui-toggle>

            <trax-ui-select v-model="selected_entity_type" :options="entity_types_select" 
                :placeholder="lang.trax_account.common.account_type" v-show="!form.admin" 
                v-if="entity_types_select.length" :disabled="selfEdit==1">
            </trax-ui-select>

            <trax-ui-select v-model="selected_organization" :options="organizations_select" 
                :unselected="lang.trax_account.common.no_organization" v-show="!form.admin && organizations_select.length"
                :disabled="selfEdit==1">
            </trax-ui-select>

            <trax-ui-select v-model="selected_entity" :options="entities_select" 
                :unselected="lang.trax_account.common.no_entity" v-show="!form.admin && entities_select.length"
                :disabled="selfEdit==1">
            </trax-ui-select>

            <trax-ui-select v-model="form.rights_level_code" :options="rights_levels_select"
                v-show="!form.admin && organizations_select.length" :disabled="selfEdit==1">
            </trax-ui-select>

            <trax-ui-select v-model="form.role_id" :options="roles_select"
                :unselected="lang.trax_account.common.no_role" v-show="!form.admin" 
                v-if="roles_select.length" :disabled="selfEdit==1">
            </trax-ui-select>

            <trax-ui-select v-model="form.user_function_code" :options="user_functions_select" 
                :unselected="lang.trax_account.common.no_function" v-if="user_functions_select.length"
                 v-show="!form.admin" :disabled="selfEdit==1">
            </trax-ui-select>

        </trax-ui-form>

    </div>
</template>

<script>
    export default {
    
        props: {
            selfEdit: null,
            manageEntities: null,
        },
        
        data: function() {
            return {
                id: 'trax-account-user-edit',
                lang: lang,

                entities_endpoint: app_url+"trax/ajax/account/entities",
                roles_select: data.roles_select,
                entity_types_select: data.entity_types_select,
                rights_levels_select: data.rights_levels_select,
                user_functions_select: data.user_functions_select,
                organizations_select: [],
                entities_select: [],

                loaded: false,
                organizations_loaded: false,
                entities_loaded: false,
                loading_finished: false,

                selected_entity_type: '',
                selected_organization: '',
                selected_entity: '',

                form: {
                    admin: '',
                    entity_type_code: '',
                    role_id: '',
                    organization_id: '',
                    entity_id: '',
                    rights_level_code: '',
                    user_function_code: ''
                },
                errors: {},
                action: {
                    align: 'right',
                    label: lang.trax_ui.form.save
                },
                bus: this.$bus
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(that.id+'-data', function(data) {
                that.selectData(data);
                if (!that.manageEntities) return;
                that.loadOrganizations();
                if (that.form.organization_id) that.loadEntities();
                else that.entities_loaded = true;
            });

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });
        },

        watch: {

            selected_entity_type: function (value) {
                if (!this.loaded) return;
                this.form.entity_type_code = value;
                this.selected_organization = '';
                this.loadOrganizations();
                this.updateRightsLevel();
            },

            selected_organization: function (value) {
                if (!this.loaded) return;
                this.form.organization_id = value;
                this.selected_entity = '';
                this.loadEntities();
                this.updateRightsLevel();
            },

            selected_entity: function (value) {
                if (!this.loaded) return;
                this.form.entity_id = value;
                this.updateRightsLevel();
            },

            loading_finished: function (value) {
                this.loaded = value;
            }
        },

        methods: {

            updateRightsLevel() {
                this.form.rights_level_code = this.form.entity_id ? 'entity' : 
                    (this.form.organization_id ? 'organization' : 'global');
            },

            loadOrganizations() {
                var that = this;
                axios.get(that.entities_endpoint, {
                    params: {
                        'search[type_code]': that.form.entity_type_code
                    }})
                    .then(function (response) {
                        var select = response.data.map(function(item, index) {
                            return {name: item.data.name, value: item.id};
                        });
                        that.organizations_select = select;
                        that.organizations_loaded = true;
                        that.checkLoaded();
                    })
            },

            loadEntities() {
                var that = this;
                axios.get(that.entities_endpoint, {
                    params: {
                        'search[type_code]': that.form.entity_type_code,
                        'search[parent_id]': that.form.organization_id
                    }})
                    .then(function (response) {
                        var select = response.data.map(function(item, index) {
                            return {name: item.data.name, value: item.id};
                        });
                        that.entities_select = select;
                        that.entities_loaded = true;
                        that.checkLoaded();
                    })
            },

            checkLoaded() {
                if (this.loaded || !this.organizations_loaded || !this.entities_loaded) return;
                this.selected_entity_type = this.form.entity_type_code;
                this.selected_entity = this.form.entity_id;
                this.selected_organization = this.form.organization_id;
                this.loading_finished = true;
            },

            selectData(data) {
                this.data = data;
                for (var index in this.form) {
                    this.form[index] = data[index];
                }
            }
        }
    }
</script>
