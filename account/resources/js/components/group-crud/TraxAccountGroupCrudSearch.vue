<template>
    <form autocomplete="off" class="trax-form-with-icons" v-on:submit.prevent="search">
    
        <!-- Status -->

        <trax-ui-select icon="cached" v-model="form.status_code" :options="status_select"
            :unselected="lang.trax_account.common.all_status">
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
                status_select: data.status_select
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
