<template>

    <div class="table-responsive">
        <table :id="id+'-table'" class="table trax-simple-table" width="100%" style="width:100%">
            <thead v-show="parseInt(header)" :class="'text-'+headerColor">
                <tr>
                    <th v-for="(title, index) in titles" :key="index" :class="colCellClass(index)">{{ title }}</th>
                </tr>
            </thead>
            <tbody v-if="rows">
                <tr v-for="(row, index2) in rows" :key="index2">
                    <td v-for="(cell, index3) in row" :key="index3" :class="colCellClass(index3)" :style="colCellStyle(index3)" v-html="cell"></td>
                </tr>
            </tbody>
        </table>
    </div>
        
</template>

<script>
    export default {    

        props: {
            id: null,
            header: { default: 1 },
            headerColor: { default: 'primary' },
            titles: null,
            rows: null,
            cols: null,
            verticalAlign: { default: 'middle' }
        },

        methods: {

            colCellClass: function(index) {
                if (!this.cols || !this.cols[index] || !this.cols[index].class) return '';
                return this.cols[index].class;
            },

            colCellStyle: function(index) {
                var style = 'vertical-align:'+this.verticalAlign;
                if (this.cols && this.cols[index]) {
                    if (this.cols[index].width) style += '; width:'+this.cols[index].width;
                    if (this.cols[index].padding) style += '; padding:'+this.cols[index].padding;
                } 
                return style;
            }
        }
    }
</script>
