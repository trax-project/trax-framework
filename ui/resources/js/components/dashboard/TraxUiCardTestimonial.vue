<template>

    <div :class="cardClass">
        <div class="icon">
            <i class="material-icons">format_quote</i>
        </div>
        <div class="card-body">
            <h5 class="card-description mb-4" style="white-space:pre-wrap;" v-if="comments">{{ comments }}</h5>
        </div>
        <div class="card-footer" v-if="userName">
            <h4 class="card-title font-weight-bold">{{ userName }}</h4>
            <h6 class="card-category">{{ userTitle }}</h6>
            <div class="card-avatar">
                <img :src="userPictureFolder+userPicture">
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            comments:  null,
            userName:  null,
            userTitle:  null,
            userPicture:  {default: 'img/lib/misc/avatar.jpg'},
            userPictureFolder: {default: app_url}
        },

        computed: {

            cardClass: function() {
                var res = 'card card-testimonial ';
                if (this.userName) res += 'mb-6';
                return res;
            }
        }

    }
</script>
