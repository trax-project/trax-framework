<template>

    <div>
        <ul :class="tabStyleComputed">
            <li v-for="item in items" :key="item.id" class="nav-item">
                <a class="nav-link" :id="item.id+'-link'" :href="'#'+item.id" data-toggle="tab">
                    <i class="material-icons">{{ item.icon }}</i>
                    {{ item.name }}
                </a>
            </li>
        </ul>
        <div class="tab-content tab-space">
            <div v-for="item in items" :key="item.id" class="tab-pane" :id="item.id">
                <slot :name="item.slot_name"></slot>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            tabs: null,
            tabStyle: {default: 'line'},
        },

        data() {
            return {
                id: 'trax-ui-horizontal-tabs-'+this.uuid(), 
                items: []
            }
        },

        computed: {
            tabStyleComputed: function() {
                var style = 'nav nav-pills nav-pills-primary pl-0 pr-0 pt-1 trax-'+this.tabStyle+'-tabs ';
                if (this.tabStyle == 'line') style += 'nav-fill ';
                return style;
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
