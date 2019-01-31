<template>

    <trax-ui-ajax-form-in-modal :id="id" :toastr-id="toastrId" :endpoint="endpoint" 
        :props="props" :titles="titles" :form="form" :bus="bus" icons="1">

        <trax-ui-select icon="list_alt" v-model="form.type_code" :options="typeCodeSelect" v-if="typeCodeSelect">
        </trax-ui-select>

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.name"
            v-model="form.name" :error="errors.name" required="1" :rows="inputRows">
        </trax-ui-input>
        
        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_ui.form.description"
            v-model="form.description" :error="errors.description" :rows="3" v-if="withDescription">
        </trax-ui-input>
        
    </trax-ui-ajax-form-in-modal>
    
</template>

<script>
    export default {

        props: ['bus', 'id', 'toastrId', 'endpoint', 'typeCode', 'typeCodeSelect', 'indexId', 'itemRows', 'withDescription'],
    
        data: function() {
            var props = {
                id: { source:'id' },
                parent_id: { source:'parent_id' },
                type_code: { source:'type_code', default: this.typeCode },
                index_id: { source:'index_id', default: this.indexId },
                order: { source:'data.order' },
                title: { source:'data.title' },
                name: { source:'data.name' }
            };
            if (this.withDescription) props.description = { source:'data.description' };
            return {
                lang: lang,
                form: {},
                errors: {},
                props: props,
                titles: {
                    new: lang.trax_ui.form.new_item,
                    update: lang.trax_ui.form.item_update
                }
            }
        },

        computed: {

            inputRows: function() {
                if (this.form.parent_id && !this.form.title && this.itemRows) return this.itemRows;
            }
        },

        created: function() {
            var that = this;

            this.bus.$on(this.id+'-errors', function(errors) {
                that.errors = errors;
            });

            this.bus.$on(this.id+'-data', function(data) {
                that.form = data;
            });
        }
    }
</script>
