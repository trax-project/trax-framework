<template>
    <div class="card card-timeline card-plain trax-dashboard-timeline-simple">
        <div class="card-body pt-0">
            <ul class="timeline pt-0 mt-0">
                <li class="timeline-inverted" v-for="(item, index) in items" :key="index">
                    <div :class="'timeline-badge '+item.color">
                        <i class="material-icons">{{ item.icon }}</i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading" v-if="item.heading" v-html="item.heading"></div>
                        <div class="timeline-body" v-if="item.body" v-html="item.body"></div>
                        <div class="timeline-footer" v-if="item.footer" v-html="item.footer"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
    
        props: ['data', 'reverse'],

        computed: {

            items: function() {
                return this.reverse ? this.data.slice().reverse() : this.data;
            }
        }
    }
</script>
