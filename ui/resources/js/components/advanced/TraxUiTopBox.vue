<template>

    <trax-ui-card-with-background vertical-align="center" :class="cardClass" v-show="loaded"
        :background-folder="backgroundFolderAttr" :background-image="backgroundImageAttr">
        
        <div v-html="title3Html"></div>
        <div v-html="title2Html"></div>
        <div v-html="title1Html"></div>
        <div v-html="descriptionHtml"></div>
        <div v-html="statusHtml"></div>
        <div class="row trax-links mb-3" v-html="linksHtml"></div>
        
        <div class="pb-3" v-if="buttonHandler && buttonName">
            <button class="btn btn-white btn-sm" @click="buttonClick">
                <i class="material-icons">{{ buttonIcon }}</i>
                {{ buttonName }}
            </button>
        </div>

        <div v-html="userHtml"></div>

    </trax-ui-card-with-background>
    
</template>

<script>
    export default {

        props: {

            // Static
            backgroundFolder: null,
            backgroundImage: null,
            icon: null,
            title1: null,
            title2: null,
            title3: null,
            description: null,
            descriptionTitle: null,
            descriptionToggle: null,
            descriptionHidden: null,
            status: null,
            statusName: null,
            link1: null,
            link2: null,
            link3: null,
            buttonIcon: null,
            buttonName: null,
            buttonHandler: null,
            userName: null,
            userPicture: null,
            userPictureFolder: {default: app_url},
            customClass: {default: ''},

            // Ajax
            endpoint: null,
            endpointParams: null,
            props: null,
            map: null,
            id: null,
            bus: null
        },

        data: function() {
            return {
                lang: lang,
                data: {},
                nativeData: {},
                loaded: false
            }
        },

        computed: {

            cardClass: function () {
                var res = this.val('userPicture') ? 'trax-top-box trax-top-box-user' : 'trax-top-box';
                return res + ' ' + this.customClass;
            },

            params: function () {
                return this.endpointParams ? this.endpointParams : {};
            },

            backgroundFolderAttr: function() {
                return this.val('backgroundFolder');
            },

            backgroundImageAttr: function() {
                return this.val('backgroundImage');
            },

            title1Html: function() {
                var title1 = this.val('title1');
                var title3 = this.val('title3');
                var icon = this.val('icon');
                if (!title1) return '';
                var iconHtml = '';
                var collapseHtml = '';
                if (this.descriptionToggle && this.descriptionHtml) {
                    collapseHtml = ' <a data-toggle="collapse" href="#trax-top-box-description" class="trax-collapse">';
                    collapseHtml += '   <i class="material-icons">help_outline</i>';
                    collapseHtml += '</a>';
                }
                if (icon) iconHtml = '<i class="trax-icon material-icons">'+icon+'</i>';
                if (title3) return '<h2 class="card-title">'+iconHtml+title1+collapseHtml+'</h2>';
                return '<h3 class="card-title">'+iconHtml+title1+collapseHtml+'</h3>';
            },

            title2Html: function() {
                var title2 = this.val('title2');
                var title3 = this.val('title3');
                if (!title2) return '';
                if (title3) return '<h4 class="card-category">'+title2+'</h4>';
                return '<h6 class="card-category">'+title2+'</h6>';
            },

            title3Html: function() {
                var title3 = this.val('title3');
                if (!title3) return '';
                return '<h6 class="card-category">'+title3+'</h6>';
            },

            descriptionHtml: function() {
                var description = this.val('description');
                var descriptionTitle = this.val('descriptionTitle');
                if (!description && !descriptionTitle) return '';
                var collapseHtml = '';
                if (this.descriptionToggle) {
                    collapseHtml += 'collapse';
                    if (!this.descriptionHidden) collapseHtml += ' show';
                }
                var content = '';
                if (descriptionTitle) content += '<h6 class="mt-3">'+descriptionTitle+'</h6>';
                if (description) content += '<p class="card-description">'+description+'</p>';
                return '<div id="trax-top-box-description" class="'+collapseHtml+'">'+content+'</div>';
            },

            statusHtml: function() {
                var status_code = this.val('status');
                if (!status_code) return '';
                var status_name = this.val('statusName');
                if (!status_name) return '';
                var color = 'info';
                if (status_code == 'active') color = 'success';
                else if (status_code == 'archived') color = 'default';
                else if (status_code == 'maintenance') color = 'warning';
                return '<span class="badge badge-'+color+' mb-3">'+status_name+'</span>';
            },

            linksHtml: function() {
                var link1 = this.val('link1');
                var link2 = this.val('link2');
                var link3 = this.val('link3');
                if (!link1) return '';
                var html = this.linkHtml(link1);
                if (link2) html += this.linkHtml(link2);
                if (link3) html += this.linkHtml(link3);
                return html;
            },

            userHtml: function() {
                var name = this.val('userName');
                var picture = this.val('userPicture');
                if (!name && !picture) return '';
                var html = '<div class="card-profile mt-0">';
                if (name) html += '<h4 class="text-white mt-0">'+name+'</h4>';
                if (picture) {
                    html += '<div class="card-avatar" style="position:relative;bottom:-50px;">';
                    html += '   <img src="' + this.userPictureFolder + picture + '" style="width:130px;height:130px;">';
                    html += '</div>';
                }
                html += '</div>';
                return html;
            }

        },

        created: function() {
            if (this.bus) this.bus.$on(this.id+'-updated', this.load);
            if (this.endpoint) this.load();
            else this.loaded = true;
        },

        methods: {

            linkHtml(link) {
                if (link == undefined) return '';
                var html = '<div class="col-md">';
                if (link.href) html += '<a href="'+link.href+'" class="btn btn-link">';  
                else html += '<h6 class="text-white text-center">'; 
                if (link.icon) html += '<i class="material-icons">'+link.icon+'</i> ';  
                html +=  link.value
                if (link.href) html += '</a>';  
                else html += '</h6>';  
                html += '</div>'; 
                return html;
            },

            val(prop) {
                if (this[prop]) {
                    if (typeof this[prop] === "function") return this[prop].call(this, this.data);
                    else return this[prop];
                }
                if (this.map && this.map[prop]) {
                    if (typeof this.map[prop] === "function") return this.map[prop].call(this, this.data);
                    else if (this.data[this.map[prop]]) return this.data[this.map[prop]];
                } 
            },

            load() {
                var that = this;
                axios.get(this.endpoint, {params: that.params})
                    .then(function (response) {
                        that.loaded = true;
                        that.setData(response.data);
                    })
                    .catch(function (error) {
                    });
            },

            buttonClick(event) {
                this.buttonHandler.call(this, this.nativeData)
            },

            setData(data) {
                this.nativeData = data;
                this.data = {};
                for (var index in this.props) {
                    var prop = this.props[index];
                    this.data[index] = this.resolveProp(prop.source, data);
                }
            },

            resolveProp(path, obj) {
                return path.split('.').reduce(function(prev, curr) {
                    return prev ? prev[curr] : null
                }, obj)
            }
        }
    }
</script>
