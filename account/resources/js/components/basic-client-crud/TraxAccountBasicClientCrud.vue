<template>
    <div>

        <!-- Table -->

        <trax-ui-card-plain-header-with-actions :title="this.lang.trax_account.common.accounts_management" :actions="card_actions" :bus="bus">
            <trax-account-basic-client-crud-table :bus="bus"></trax-account-basic-client-crud-table>
        </trax-ui-card-plain-header-with-actions>

        <!-- Editing modal -->

        <trax-ui-modal-default id="trax-account-basic-client-edit" :bus="bus">
            <trax-account-basic-client-crud-form :bus="bus"></trax-account-basic-client-crud-form>
        </trax-ui-modal-default>

        <!-- Delete confirm modal -->

        <trax-ui-modal-confirm id="trax-account-basic-client-delete" :title="this.lang.trax_account.common.client_deletion" :bus="bus">
            {{ lang.trax_account.common.confirm_delete_account_q }}
        </trax-ui-modal-confirm>
    
        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-basic-client-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>
    
    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                lang: lang,
                card_actions: [
                    { icon: 'add_box', event: 'trax-account-basic-client-edit-create' }
                ],
                bus: new Vue()
            }
        }
    }
</script>
