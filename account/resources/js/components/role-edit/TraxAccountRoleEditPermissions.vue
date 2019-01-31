<template>
    <div>

        <trax-ui-form :id="id" :form="form" :action="action" :bus="bus">

            <trax-ui-checkbox v-for="permission in permissions_select" :key="permission.value" 
                :text="permission.name" v-model="form.permissions[permission.value]">
            </trax-ui-checkbox>

        </trax-ui-form>

    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                id: 'trax-account-role-edit',
                lang: lang,
                permissions_select: data.permissions_select,
                form: {
                    permissions: data.role_permissions_default
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
            });

            this.bus.$on(that.id+'-errors', function(errors) {
                that.errors = errors;
            });
        },

        methods: {

            selectData(data) {
                if (data.permissions) {
                    for (var index in data.permissions) {
                        this.form.permissions[index] = data.permissions[index];
                    }
                }
            }
        }
    }
</script>
