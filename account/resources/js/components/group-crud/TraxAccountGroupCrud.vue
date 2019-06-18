<template>
    <div>

        <!-- Table -->

        <trax-ui-card-plain-header-with-actions :title="this.lang.trax_account.common.groups_management" :actions="card_actions" :bus="bus">
            <trax-account-group-crud-table :bus="bus"></trax-account-group-crud-table>
        </trax-ui-card-plain-header-with-actions>

        <!-- Editing modal -->

        <trax-ui-modal-default id="trax-account-group-edit" :bus="bus">
            <trax-account-group-crud-form :bus="bus"></trax-account-group-crud-form>
        </trax-ui-modal-default>

        <!-- Delete confirm modal -->

        <trax-ui-modal-confirm id="trax-account-group-delete" :title="this.lang.trax_account.common.group_deletion" :bus="bus">
            {{ lang.trax_account.common.confirm_delete_group_q }}
        </trax-ui-modal-confirm>

        <!-- Search modal -->

        <trax-ui-modal-default id="trax-account-group-search" :bus="bus" :title="lang.trax_ui.form.advanced_search">
            <trax-account-group-crud-search id="trax-account-group" :bus="bus"></trax-account-group-crud-search>
        </trax-ui-modal-default>

        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-group-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>
    
    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                lang: lang,
                card_actions: [
                    { icon: 'search', event: 'trax-account-group-search-open' },
                    { icon: 'add_box', event: 'trax-account-group-edit-create' }
                ],
                bus: new Vue()
            }
        }
    }
</script>
