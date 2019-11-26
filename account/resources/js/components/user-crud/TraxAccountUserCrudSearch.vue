<template>
    <form autocomplete="off" class="trax-form-with-icons" v-on:submit.prevent="search">
    
        <!-- Role -->

        <trax-ui-select icon="account_box" v-model="form.role_id" :options="roles_select"
            :unselected="lang.trax_account.common.all_roles">
        </trax-ui-select>

        <!-- Submit buttons -->

        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" @click="reset"> {{ lang.trax_ui.form.reset }} </button>
            <button type="submit" class="btn btn-primary btn-round"> {{ lang.trax_ui.form.search }} </button>
        </div>

    </form>
</template>

<script>
    export default {
    
        props: {
            id: null,
            bus: null
        },
        
        data: function() {
            return {
                lang: lang,
                form: {},
                roles_select: data.roles_select
            }
        },
        
        created: function() {
            var that = this;

            this.bus.$on(this.id+'-filter-restore', function (data) {
                that.form = data;
            });
        },
        
        methods: {

            search() {
                this.bus.$emit(this.id+'-filter', this.form);
                this.bus.$emit(this.id+'-search-close');
            },

            reset() {
                this.form = {};
                this.search();
            }
        }
            
    }
</script>
