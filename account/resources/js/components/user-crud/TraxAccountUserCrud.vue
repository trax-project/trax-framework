<template>
    <div>

        <!-- Table -->

        <trax-ui-card-plain-header-with-actions :title="this.lang.trax_account.common.accounts_management" :actions="card_actions" :bus="bus">
            <trax-account-user-crud-table :bus="bus" :with-username="withUsername"></trax-account-user-crud-table>
        </trax-ui-card-plain-header-with-actions>

        <!-- Editing modal -->

        <trax-ui-modal-default id="trax-account-user-edit" :bus="bus">
            <trax-account-user-crud-form :bus="bus" :with-username="withUsername"></trax-account-user-crud-form>
        </trax-ui-modal-default>

        <!-- Delete confirm modal -->

        <trax-ui-modal-confirm id="trax-account-user-delete" :title="this.lang.trax_account.common.user_deletion" :bus="bus">
            {{ lang.trax_account.common.confirm_delete_account_q }}
        </trax-ui-modal-confirm>
    
        <!-- Search modal -->

        <trax-ui-modal-default id="trax-account-user-search" :bus="bus" :title="lang.trax_ui.form.advanced_search">
            <trax-account-user-crud-search id="trax-account-user" :bus="bus"></trax-account-user-crud-search>
        </trax-ui-modal-default>

        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-user-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>
    
    </div>
</template>

<script>
    export default {
    
        props: ['withUsername'],
        
        data: function() {
            return {
                lang: lang,
                card_actions: [
                    { icon: 'search', event: 'trax-account-user-search-open' },
                    { icon: 'add_box', event: 'trax-account-user-edit-create' }
                ],
                bus: new Vue()
            }
        }
    }
</script>
