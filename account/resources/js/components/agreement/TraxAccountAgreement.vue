<template>

    <trax-ui-card>

        <div v-html="agreementContent"></div>

        <div class="trax-form-actions mb-3 mt-4 text-center" v-if="approveButton && agreement">

            <button type="button" class="btn btn-round btn-primary" @click="approve"> 
                {{ lang.trax_account.common.user_agreement_approve }} 
            </button>

        </div>

    </trax-ui-card>

</template>

<script>
    export default {
    
        props: {
            id: {default: 'trax-account-agreement'},
            approveButton: null
        },

        data: function() {
            return {
                lang: lang,
                user: user,
                app_url: app_url,
                agreement: data.agreement,
                bus: this.$bus
            }
        },

        computed: {

            agreementContent() {
                if (this.agreement) return this.agreement.content;
                return '<p>'+this.lang.trax_account.common.user_agreement_empty+'</p>';
            },

            endpoint() {
                if (!this.agreement) return null;
                return this.app_url+'trax/ajax/account/users/'+this.user.id+'/agreements/register';
            }
        },

        methods: {

            approve(data) {
                var that = this;
                axios.post(this.endpoint, { 
                        member_id: that.agreement.id, 
                    })
                    .then(function (response) {
                        window.location = that.app_url;
                    })
                    .catch(function (error) {
                    });
            },

        }
    }
</script>
