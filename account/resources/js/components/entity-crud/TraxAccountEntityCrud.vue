<template>
    <div>

        <!-- Tabs and struct -->

        <trax-ui-vertical-dynamic-tabs :tabs="tabs" id="trax-account-entity-tabs" :bus="bus" class="pt-3 pb-3">
            <trax-ui-ajax-struct-with-actions id="trax-account-entity" :bus="bus" :endpoint="endpoint" 
                :new-item-label="lang.trax_account.common.new_entity" 
                :new-category-label="lang.trax_account.common.new_organization">
            </trax-ui-ajax-struct-with-actions>
        </trax-ui-vertical-dynamic-tabs>

        <!-- Editing modal -->

        <trax-ui-modal-default id="trax-account-entity-edit" :bus="bus">
            <trax-ui-struct-item-form id="trax-account-entity-edit" :bus="bus" :endpoint="endpoint">
            </trax-ui-struct-item-form>
        </trax-ui-modal-default>

        <!-- Delete confirm modal -->

        <trax-ui-modal-confirm id="trax-account-entity-delete" :title="this.lang.trax_ui.form.deletion" :bus="bus">
            {{ lang.trax_ui.form.confirm_delete_q }}
        </trax-ui-modal-confirm>

        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-entity-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>
    
    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/entities",
                tabs: data.entity_types_select,
                bus: new Vue()
            }
        }
    }
</script>
