<template>

    <div class="row" v-if="tabs.length">
        <div class="col-lg-4 col-md-5 col-sm-6">
            <ul class="nav nav-pills nav-pills-primary flex-column">
                <li v-for="item in items" :key="item.id" class="nav-item">
                    <a class="nav-link" :id="item.id+'-link'" :href="'#'+id" data-toggle="tab" @click="tabChanged">{{ item.name }}</a>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-7 col-sm-6">
            <div class="tab-content">
                <div class="tab-pane" :id="id">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <slot></slot>
    </div>
    
</template>

<script>
    export default {

        props: ['tabs', 'id', 'bus'],

        data() {
            return {
                items: []
            }
        },

        created() {
            for (var index in this.tabs) {
                var tab = this.tabs[index];
                tab.id = this.id + '-' + tab.value;
                this.items.push(tab);
            }
        },

        mounted() {
            if (!this.tabs.length) {
                this.bus.$emit(this.id+'-changed');
                return;
            }
            var first = this.tabs[0];
            $('#'+this.id).addClass('active');
            $('#'+first.id+'-link').addClass('active');
            this.bus.$emit(this.id+'-changed', first.value);
        },

        methods: {

            tabChanged(event) {
                for (var index in this.items) {
                    if (this.items[index].id+'-link' == event.target.id) {
                        this.bus.$emit(this.id+'-changed', this.items[index].value);
                        return;
                    }
                }
            }
        }
    }
</script>
