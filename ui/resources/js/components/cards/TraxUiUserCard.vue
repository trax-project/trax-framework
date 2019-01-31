<template>

    <div class="card card-profile mt-5">
        <div class="card-avatar">
            <img :src="userPictureFolder+(userPicture ? userPicture : userPictureDefault)" style="width:130px;height:130px;">
        </div>
        <div :class="'card-body text-'+align">
            <h4 class="card-title mb-3 text-center font-weight-bold" v-html="userName"></h4>
            <slot></slot>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            align:  {default: 'left'},
            userName:  {default: ''},
            userPictureFolder: {default: app_url},
            userPictureDefault: {default: 'img/lib/misc/avatar.jpg'},
            userPicture: null,
        }

    }
</script>
