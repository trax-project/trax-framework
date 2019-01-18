<template>

    <div class="row">
        <div class="col-lg-4 col-md-5 col-sm-6" :style="leftColStyle">
            <ul class="nav nav-pills nav-pills-primary flex-column">
                <li v-for="item in items" :key="item.id" class="nav-item">
                    <a class="nav-link" :id="item.id+'-link'" :href="'#'+item.id" data-toggle="tab">{{ item.name }}</a>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-7 col-sm-6" :style="rightColStyle">
            <div class="tab-content">
                <div v-for="item in items" :key="item.id" class="tab-pane" :id="item.id">
                    <slot :name="item.slot_name"></slot>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            tabs: null,
            leftColStyle: null,
            rightColStyle: null
        },

        data() {
            return {
                id: 'trax-ui-vertical-tabs-'+this.uuid(), 
                items: []
            }
        },

        created() {
            for (var index in this.tabs) {
                var tab = this.tabs[index];
                index = parseInt(index);
                tab.id = this.id + '-' + tab.value;
                tab.slot_name = 'tab-'+(index+1);
                this.items.push(tab);
            }
        },

        mounted() {
            var first = this.tabs[0];
            $('#'+first.id).addClass('active');
            $('#'+first.id+'-link').addClass('active');
        },

        methods: {

            uuid() {
                var res = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                    return v.toString(16);
                });
                return res;
            }
        }
    }
</script>
